#!/bin/bash

# Script untuk cleanup container lama sebelum deploy
# Ini mencegah conflict error saat Dokploy atau docker-compose up

echo "🧹 Cleaning up old board-sapa containers..."

# Array container names yang perlu dihapus
CONTAINERS=("board-sapa-app" "board-sapa-nginx" "board-sapa-redis")

for container in "${CONTAINERS[@]}"; do
    # Cek apakah container exist
    if docker ps -a --format "{{.Names}}" | grep -q "^${container}$"; then
        echo "⏹️  Stopping $container..."
        docker stop "$container" 2>/dev/null || true
        
        echo "🗑️  Removing $container..."
        docker rm "$container" 2>/dev/null || true
        
        echo "✅ Removed: $container"
    fi
done

echo "✨ Cleanup complete! Safe to deploy now."
