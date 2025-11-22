import torch
import torch.nn as nn
import torch.nn.functional as F
from torch_geometric.nn import HGTConv

class HGTModel(nn.Module):
    """
    Heterogeneous Graph Transformer (HGT) untuk Adaptive Learning
    
    Model ini menggunakan arsitektur HGT untuk memodelkan hubungan antara:
    - Student nodes
    - Module nodes
    - Question nodes
    - Performance edges
    """
    
    def __init__(self, in_dim, hidden_dim, out_dim, num_heads=4, num_layers=2):
        """
        Args:
            in_dim: Dimensi input features
            hidden_dim: Dimensi hidden layer
            out_dim: Dimensi output (jumlah level: 3)
            num_heads: Jumlah attention heads
            num_layers: Jumlah HGT layers
        """
        super(HGTModel, self).__init__()
        
        self.in_dim = in_dim
        self.hidden_dim = hidden_dim
        self.out_dim = out_dim
        self.num_heads = num_heads
        self.num_layers = num_layers
        
        # Input projection
        self.input_proj = nn.Linear(in_dim, hidden_dim)
        
        # HGT Layers
        self.hgt_layers = nn.ModuleList()
        for _ in range(num_layers):
            self.hgt_layers.append(
                HGTLayer(hidden_dim, hidden_dim, num_heads)
            )
        
        # Output layers
        self.fc1 = nn.Linear(hidden_dim, hidden_dim // 2)
        self.dropout = nn.Dropout(0.3)
        self.fc2 = nn.Linear(hidden_dim // 2, out_dim)
        
        # Batch normalization
        self.bn = nn.BatchNorm1d(hidden_dim)
        
    def forward(self, x):
        """
        Forward pass
        
        Args:
            x: Input features [batch_size, in_dim]
            
        Returns:
            Output predictions [batch_size, out_dim]
        """
        # Input projection
        x = self.input_proj(x)
        x = F.relu(x)
        
        # Apply HGT layers
        for layer in self.hgt_layers:
            x = layer(x)
            x = F.relu(x)
        
        # Batch normalization
        if x.size(0) > 1:
            x = self.bn(x)
        
        # Output layers
        x = self.fc1(x)
        x = F.relu(x)
        x = self.dropout(x)
        x = self.fc2(x)
        
        return x

class HGTLayer(nn.Module):
    """
    Single HGT Layer dengan multi-head attention
    """
    
    def __init__(self, in_dim, out_dim, num_heads):
        super(HGTLayer, self).__init__()
        
        self.in_dim = in_dim
        self.out_dim = out_dim
        self.num_heads = num_heads
        self.head_dim = out_dim // num_heads
        
        # Attention parameters
        self.W_q = nn.Linear(in_dim, out_dim)
        self.W_k = nn.Linear(in_dim, out_dim)
        self.W_v = nn.Linear(in_dim, out_dim)
        
        # Output projection
        self.W_o = nn.Linear(out_dim, out_dim)
        
        # Layer normalization
        self.layer_norm = nn.LayerNorm(out_dim)
        
    def forward(self, x):
        """
        Forward pass for HGT layer
        
        Args:
            x: Input features [batch_size, in_dim]
            
        Returns:
            Output features [batch_size, out_dim]
        """
        batch_size = x.size(0)
        
        # Multi-head attention
        Q = self.W_q(x).view(batch_size, self.num_heads, self.head_dim)
        K = self.W_k(x).view(batch_size, self.num_heads, self.head_dim)
        V = self.W_v(x).view(batch_size, self.num_heads, self.head_dim)
        
        # Scaled dot-product attention
        scores = torch.matmul(Q, K.transpose(-2, -1)) / (self.head_dim ** 0.5)
        attention = F.softmax(scores, dim=-1)
        
        # Apply attention to values
        context = torch.matmul(attention, V)
        context = context.view(batch_size, -1)
        
        # Output projection
        output = self.W_o(context)
        
        # Residual connection and layer norm
        output = self.layer_norm(output + x)
        
        return output

class StudentEncoder(nn.Module):
    """
    Encoder untuk student node features
    """
    
    def __init__(self, in_dim, out_dim):
        super(StudentEncoder, self).__init__()
        self.fc = nn.Sequential(
            nn.Linear(in_dim, out_dim),
            nn.ReLU(),
            nn.Dropout(0.2),
            nn.Linear(out_dim, out_dim)
        )
    
    def forward(self, x):
        return self.fc(x)

class ModuleEncoder(nn.Module):
    """
    Encoder untuk module node features
    """
    
    def __init__(self, in_dim, out_dim):
        super(ModuleEncoder, self).__init__()
        self.fc = nn.Sequential(
            nn.Linear(in_dim, out_dim),
            nn.ReLU(),
            nn.Dropout(0.2),
            nn.Linear(out_dim, out_dim)
        )
    
    def forward(self, x):
        return self.fc(x)

class PerformancePredictor(nn.Module):
    """
    Predictor untuk performance level
    """
    
    def __init__(self, in_dim, num_levels=3):
        super(PerformancePredictor, self).__init__()
        self.predictor = nn.Sequential(
            nn.Linear(in_dim, in_dim // 2),
            nn.ReLU(),
            nn.Dropout(0.3),
            nn.Linear(in_dim // 2, num_levels)
        )
    
    def forward(self, x):
        return self.predictor(x)

def create_heterogeneous_graph(student_data, module_data, performance_data):
    """
    Membuat heterogeneous graph dari data
    
    Args:
        student_data: Data siswa
        module_data: Data modul
        performance_data: Data performa (edges)
        
    Returns:
        HeteroData object untuk PyTorch Geometric
    """
    # Implementasi untuk membuat graph structure
    # Akan digunakan untuk training model
    pass

if __name__ == "__main__":
    # Test model
    model = HGTModel(in_dim=64, hidden_dim=128, out_dim=3, num_heads=4, num_layers=2)
    
    # Create dummy input
    x = torch.randn(1, 64)
    
    # Forward pass
    output = model(x)
    print(f"Model output shape: {output.shape}")
    print(f"Model output: {output}")
    
    # Apply softmax to get probabilities
    probs = F.softmax(output, dim=-1)
    print(f"Probabilities: {probs}")
    print(f"Predicted level: {torch.argmax(probs, dim=-1).item() + 1}")
