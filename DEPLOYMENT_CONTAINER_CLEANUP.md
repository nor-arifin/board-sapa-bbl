# 📋 DEPLOYMENT GUIDE - Container Conflict Prevention

## ⚠️ Issue yang Terjadi
Container `board-sapa-redis`, `board-sapa-app`, dan `board-sapa-nginx` tidak otomatis dihapus saat redeploy, menyebabkan conflict error:
```
The container name "/board-sapa-redis" is already in use by container...
```

## ✅ Solusi

### Option 1: Automated Cleanup Script (Recommended)
Jalankan script ini **sebelum deploy** melalui Dokploy:

```bash
cd /home/sapabbl/board-sapa-bbl
./pre-deploy-cleanup.sh
```

Script ini akan:
- ✅ Menghapus semua old containers (`app`, `nginx`, `redis`)
- ✅ Prune unused Docker resources
- ✅ Show network status
- ✅ Siap untuk deploy fresh

### Option 2: Manual Cleanup
Jika ingin manual cleanup saja:

```bash
cd /home/sapabbl/board-sapa-bbl
./cleanup-containers.sh
```

### Option 3: Docker Compose Down
Untuk cleanup yang lebih comprehensive:

```bash
cd /home/sapabbl/board-sapa-bbl
docker-compose down --volumes  # Remove containers + volumes
```

## 🔧 Permanent Solution: Update Dokploy

Di Dokploy dashboard, tambahkan pre-deployment hook:

```bash
#!/bin/bash
cd /app && ./pre-deploy-cleanup.sh
```

atau minimal:

```bash
#!/bin/bash
docker stop board-sapa-app board-sapa-nginx board-sapa-redis 2>/dev/null || true
docker rm board-sapa-app board-sapa-nginx board-sapa-redis 2>/dev/null || true
```

## 📌 Langkah-Langkah Deploy Proper

1. **Pre-deployment cleanup:**
   ```bash
   ./pre-deploy-cleanup.sh
   ```

2. **Deploy via Dokploy UI** atau:
   ```bash
   docker-compose up -d
   ```

3. **Verify containers running:**
   ```bash
   docker ps | grep board-sapa
   ```

## 🚀 Long-term Prevention

Tambahkan ke `.env` atau konfigurasi Dokploy:
- Set container auto-removal policy
- Add health checks
- Configure restart policies dengan proper cleanup

## 📚 Related Files
- [cleanup-containers.sh](cleanup-containers.sh) - Script cleanup individual containers
- [pre-deploy-cleanup.sh](pre-deploy-cleanup.sh) - Full pre-deployment cleanup
- [docker-compose.yml](docker-compose.yml) - Main compose file

---
**Last Updated**: 2026-01-22
