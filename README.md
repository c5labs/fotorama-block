# Fotorama Block for Concrete5 #
The packages adds a block that adds the [fotorama](http://fotorama.io) gallery to a page.

**This package is for concrete 5.7.1+ (version 8 compatible)**

## Installation ##

1. Download zipball from [here](https://github.com/c5labs/fotorama-block/releases) and unzip.
1. Copy the 'fotorama-block' folder to your concrete5 installations 'packages' folder.
4. Login, click on the Settings icon on the right of the top bar, click 'Extend concrete5'.
5. Click on the 'Install' button next to 'Fotorama Components Package'.
6. Go to a page, click the add a block button, look for 'Fotorama' under the multimedia section.

## Upgrading to 0.11.0 from earlier versions ##
**Before** trying to run the upgrade from the dashboard:
1. Remove the old 'fotorama_package' or 'concrete-fotorama-block' directory from your installations packages directory.
2. Copy the new 'fotorama-block' in.
3. Open your favourite database manipulation tool, goto the packages table, find the 'Fotorama Block Components' row and change the `pkgHandle` from 'fotorama_package' or 'concrete-fotorama-block' to 'fotorama-block'.
4. Goto the dashboard and run the package upgrade.

## License ##
MIT. See attached license file.
