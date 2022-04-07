all: rebuild

rebuild: clean build

build:
	tar zcvf HelloWorld2.tar.gz ./HelloWorld2/

clean:
	rm -f ./HelloWorld2.tar.gz
