@echo off
:: Check if script is running as admin
net session >nul 2>&1
if %errorLevel% == 0 (
  echo Running PowerShell as Administrator...
  powershell.exe -NoExit -Command "Set-ExecutionPolicy RemoteSigned -Scope CurrentUser; cd '%cd%'"
) else (
  echo Requesting Administrator access...
  powershell.exe -Command "Start-Process cmd -ArgumentList '/c \"%~f0\"' -Verb runAs"
)
