@echo off
set GOPATH=%~dp0;D:\WWW\sxgames2
if "%1"=="" goto :1
%~d0
cd %~dp0

del /a /f .\bin\api.exe
del /a /f .\bin\collection.exe

::go get  github.com/shirou/gopsutil
::go build -ldflags “-s -w” 删除调试符号,发布
::go build -gcflags “-N -l” 关闭内联优化
go build -ldflags "-X main._VERSION_ '%1' "  -o .\bin\api.exe .\src\bin\api
go build -ldflags "-X main._VERSION_ '%1' "  -o .\bin\collection.exe .\src\bin\collection

cd bin
.\api.exe
cd ..
goto :9

:1
echo "place select a version to build...\n\n"
goto :9

:9
set GOPATH=D:\WWW\sxgames2