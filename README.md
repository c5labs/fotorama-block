#Fotorama Block for Concrete5#
The packages adds a block that adds the [fotorama](http://fotorama.io) gallery to a page.

**This package is for concrete 5.7.1+**

##Installation##

1. Download zipball from [here](https://github.com/olsgreen/concrete-fotorama-block/archive/master.zip) and unzip.
1. Copy the 'concrete-fotorama-block' folder to your concrete5 installations 'packages' folder.
4. Login, click on the Settings icon on the right of the top bar, click 'Extend concrete5'.
5. Click on the 'Install' button next to 'Fotorama Components Package'.
6. Go to a page, click the add a block button, look for 'Fotorama' under the multimedia section.

##Upgrading to 0.10.0 from 0.9.x##
1. Remove the old 'fotorama_package' directory from your installations packages directory.
2. Copy the new 'concrete-fotorama-block' in.
3. Open your favourite database manipulation tool, goto the packages table, find the 'Fotorama Block Components' row and change the `pkgHandle` from 'fotorama_package' to 'concrete-fotorama-block'.

## License ##
MIT. See attached license file.