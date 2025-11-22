from flask import Flask, request, jsonify
from flask_cors import CORS
import torch
import torch.nn.functional as F
import numpy as np
from hgt_model import HGTModel
import json
from datetime import datetime

app = Flask(__name__)
CORS(app)

# Initialize HGT Model
model = HGTModel(
    in_dim=64,
    hidden_dim=128,
    out_dim=3,  # 3 levels: rendah, sedang, tinggi
    num_heads=4,
    num_layers=2
)

# Load pretrained model if exists
try:
    model.load_state_dict(torch.load('models/hgt_adaptive_model.pth', weights_only=False))
    model.eval()
    print("Model loaded successfully!")
except Exception as e:
    print(f"No pretrained model found. Using fresh model. Error: {e}")

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint"""
    return jsonify({
        'status': 'ok',
        'timestamp': datetime.now().isoformat(),
        'model': 'HGT Adaptive Learning'
    })

@app.route('/predict', methods=['POST'])
def predict_level():
    """
    Prediksi level kemampuan siswa berdasarkan hasil pretest
    
    Input:
    {
        "student_id": int,
        "module_results": [
            {
                "module_id": int,
                "correct_answers": int,
                "total_questions": int
            }
        ]
    }
    
    Output:
    {
        "student_id": int,
        "predictions": [
            {
                "module_id": int,
                "score": int,
                "predicted_level": int,
                "confidence": float
            }
        ],
        "overall_level": int,
        "recommended_start_module": int
    }
    """
    try:
        data = request.get_json()
        student_id = data.get('student_id')
        module_results = data.get('module_results', [])
        
        if not module_results:
            return jsonify({'error': 'No module results provided'}), 400
        
        predictions = []
        scores = []
        
        for module in module_results:
            module_id = module['module_id']
            correct = module['correct_answers']
            total = module['total_questions']
            
            # Hitung score berdasarkan ketentuan
            if correct == 0:
                score = 0
            elif correct == 1:
                score = 50
            elif correct == 2:
                score = 85
            elif correct >= 3:
                score = 100
            else:
                score = 0
            
            # Prepare features for GNN
            features = prepare_features(student_id, module_id, correct, total, score)
            
            # Get prediction from model
            with torch.no_grad():
                output = model(features)
                probs = F.softmax(output, dim=-1)
                predicted_level = torch.argmax(probs, dim=-1).item() + 1  # 1, 2, or 3
                confidence = probs[0][predicted_level - 1].item()
            
            predictions.append({
                'module_id': module_id,
                'score': score,
                'correct_answers': correct,
                'total_questions': total,
                'predicted_level': predicted_level,
                'confidence': float(confidence)
            })
            
            scores.append(score)
        
        # Calculate overall level based on average score
        avg_score = np.mean(scores)
        if avg_score >= 85:
            overall_level = 3  # Tinggi
        elif avg_score >= 50:
            overall_level = 2  # Sedang
        else:
            overall_level = 1  # Rendah
        
        # Recommend starting module based on lowest score
        min_score_module = min(predictions, key=lambda x: x['score'])
        
        return jsonify({
            'student_id': student_id,
            'predictions': predictions,
            'overall_level': overall_level,
            'average_score': float(avg_score),
            'recommended_start_module': min_score_module['module_id'],
            'timestamp': datetime.now().isoformat()
        })
        
    except Exception as e:
        return jsonify({
            'error': str(e),
            'timestamp': datetime.now().isoformat()
        }), 500

@app.route('/calculate_score', methods=['POST'])
def calculate_score():
    """
    Hitung score untuk satu modul
    
    Input:
    {
        "correct_answers": int,
        "total_questions": int
    }
    
    Output:
    {
        "score": int
    }
    """
    try:
        data = request.get_json()
        correct = data.get('correct_answers', 0)
        total = data.get('total_questions', 3)
        
        # Hitung score berdasarkan ketentuan
        if correct == 0:
            score = 0
        elif correct == 1:
            score = 50
        elif correct == 2:
            score = 85
        elif correct >= 3:
            score = 100
        else:
            score = 0
        
        return jsonify({
            'correct_answers': correct,
            'total_questions': total,
            'score': score
        })
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

def prepare_features(student_id, module_id, correct, total, score):
    """
    Prepare features for HGT model
    """
    # Create feature vector
    features = torch.zeros(1, 64)
    
    # Encode student info
    features[0, 0] = student_id / 1000.0  # Normalize
    
    # Encode module info
    features[0, 1] = module_id / 10.0  # Normalize
    
    # Encode performance
    features[0, 2] = correct / total if total > 0 else 0
    features[0, 3] = score / 100.0
    
    # Additional features can be added based on graph structure
    # For example: student's previous performance, module difficulty, etc.
    
    return features

@app.route('/train', methods=['POST'])
def train_model():
    """
    Endpoint untuk training model dengan data baru
    (Untuk pengembangan lanjutan)
    """
    return jsonify({
        'status': 'Training endpoint - To be implemented',
        'message': 'Model training will be implemented in future updates'
    })

if __name__ == '__main__':
    import os
    port = int(os.environ.get('PORT', 5001))  # Default port 5001
    try:
        print(f"\n{'='*50}")
        print(f"🚀 Starting GNN Adaptive Learning API")
        print(f"{'='*50}")
        print(f"📡 Server: http://localhost:{port}")
        print(f"🏥 Health: http://localhost:{port}/health")
        print(f"🧠 Predict: http://localhost:{port}/predict")
        print(f"{'='*50}\n")
        app.run(host='0.0.0.0', port=port, debug=True)
    except OSError as e:
        if 'Address already in use' in str(e) or 'access permission' in str(e).lower():
            print(f"\n❌ Error: Port {port} is already in use or access denied!")
            print(f"\n💡 Solutions:")
            print(f"   1. Try different port: set PORT=5002 && python app.py")
            print(f"   2. Kill process: netstat -ano | findstr :{port}")
            print(f"   3. Run as Administrator\n")
        raise
