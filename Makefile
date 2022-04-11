all: rebuild

rebuild: clean build

build:
	tar zcvf ReoccuringConfig.tar.gz ./ReoccuringConfig/

clean:
	rm -f ./ReoccuringConfig.tar.gz
