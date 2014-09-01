#
# Moodle/Totara LMS CPD plugin
#
# @author Luke Carrier <luke@carrier.im> <luke@tdm.co>
# @copyright (c) 2014 The Development Manager Ltd
#

.PHONY: all clean

TOP := $(dir $(CURDIR)/$(word $(words $(MAKEFILE_LIST)), $(MAKEFILE_LIST)))

all: build/local_cpd.zip

clean:
	rm -rf $(TOP)build

build/local_cpd.zip:
	mkdir -p $(TOP)build
	cp -rv $(TOP)src $(TOP)build/cpd
	cp $(TOP)README.md $(TOP)build/cpd/README.txt
	cd $(TOP)build \
		&& zip -r local_cpd.zip cpd
	rm -rfv $(TOP)build/cpd
