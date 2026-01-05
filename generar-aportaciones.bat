@echo off
echo ========================================
echo Generacion de Aportaciones Mensuales
echo Sistema de Gestion del Colegio Profesional
echo ========================================
echo.
echo Iniciando proceso...
echo.

cd /d "%~dp0"
C:\xampp\php\php.exe public\cron\generar-aportaciones-mensual.php

echo.
echo ========================================
echo Proceso finalizado
echo ========================================
echo.
echo Presiona cualquier tecla para salir...
pause > nul
