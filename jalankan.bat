@echo off
title CariKos App Runner
echo ====================================================
echo   MENYALAKAN CARIKOS (DATABASE MYSQL + SERVER WEB)
echo ====================================================

:: Cek apakah MySQL sudah aktif di port 3306
netstat -ano | find "3306" | find "LISTENING" >nul
if %errorlevel% neq 0 (
    echo [1/2] Menyalakan MySQL XAMPP di background...
    start /b "" "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
    timeout /t 2 /nobreak >nul
) else (
    echo [1/2] Database MySQL sudah aktif (Port 3306).
)

echo [2/2] Menjalankan server web Laravel (php artisan serve)...
echo ----------------------------------------------------
echo  Aplikasi siap dibuka di browser: http://127.0.0.1:8000
echo  Tekan Ctrl+C untuk menghentikan server web.
echo ----------------------------------------------------
php artisan serve
