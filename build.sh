#!/bin/bash

WHERE=`pwd`

TGZ_NAME="xmlclasseslib-1.0.2.tgz"
DIR_NAME="xmlclasseslib"

cd ..
tar -cvz --exclude=OLD --exclude=work --exclude=*~ --exclude=CVS --exclude=.?* --exclude=np --exclude=.cvsignore -f $TGZ_NAME $DIR_NAME
cd "$WHERE"
