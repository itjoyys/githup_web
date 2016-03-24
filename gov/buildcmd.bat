@echo off
set GOPATH=%~dp0;D:\App\gopath
if "%1"=="" goto :1
%~d0
cd %~dp0

del /a /f .\bin\api.exe

::go get  github.com/shirou/gopsutil
::go build -ldflags 
::go build -gcflags -ldflags
go build -ldflags "-X main._VERSION_ '%1' "  -o .\bin\api.exe .\src\bin\api
go build -ldflags "-X main._VERSION_ '%1' "  -o .\bin\collection.exe .\src\bin\collection

cd bin
::.\cmd.exe
cd ..
goto :9

:1
echo "place select a version to build...\n\n"
goto :9

:9
set GOPATH=D:\App\gopath