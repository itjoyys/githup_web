@echo off
set GOPATH=%~dp0;D:\WWW\sxgames2
if "%1"=="" goto :1
%~d0
cd %~dp0

del /a /f .\bin\pt.exe

::go get  github.com/shirou/gopsutil
go build -ldflags "-X main._VERSION_ '%1' "  -o .\bin\pt.exe .\src\webserver\pt

cd bin
.\pt.exe
cd ..
goto :9

:1
echo "place select a version to build...\n\n"
goto :9

:9
set GOPATH=D:\WWW\sxgames2