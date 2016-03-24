#!/bin/sh
ProjectPath=`pwd`
export GOPATH=$ProjectPath:$GOPATH
#SrcPath=$ProjectPath/src
if [ -z "$1" ]
then
	echo "place select a version to build...\n\n"
	exit 1
fi

rm -f $ProjectPath/bin/dz

cd $ProjectPath
echo  $1 "version building..."
##-gcflags "-N -l"
go build -ldflags "-X main._VERSION_ '$1' "  -o ./bin/dz ./src/webserver/dz

if [ "X$1" = "Xonline" ]
then
cd $ProjectPath

else
cd $ProjectPath/src
../bin/dz
fi

#调试的时候使用
echo  $1 "version build finish"
