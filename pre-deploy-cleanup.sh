#!/bin/bash

# Pre-deployment script untuk cleanup dan siap deploy
# Jalankan ini sebelum deploy: ./pre-deploy-cleanup.sh

set -e  # Exit on error

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "================================"
echo "🚀 Pre-Deploy Cleanup Script"
echo "================================"
echo ""

# Step 1: Cleanup containers
echo "1️⃣  Cleaning up old containers..."
"$SCRIPT_DIR/cleanup-containers.sh"
echo ""

# Step 2: Prune unused docker resources
echo "2️⃣  Pruning unused Docker resources..."
docker system prune -f --volumes 2>/dev/null || true
echo "✅ Docker resources pruned"
echo ""

# Step 3: Show network status
echo "3️⃣  Docker networks status:"
docker network ls | grep -E "(sapa-network|dokploy-network)" || echo "⚠️  Networks not found (will be created on deploy)"
echo ""

echo "================================"
echo "✨ Pre-deployment cleanup complete!"
echo "================================"
echo ""
echo "📝 You can now:"
echo "   1. Deploy through Dokploy UI"
echo "   2. Or run: docker-compose up -d"
