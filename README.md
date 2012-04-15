# Ghendetta

## What is Ghendetta?

Ghendetta (contraction of Ghent & Vendetta) is an application which is a result of the studenthackathon at Apps For Ghent 2012 <http://appsforghent.be>. Team iRail came up with this idea and kept developing this concept. The entire site is built on the CodeIgniter php framework.

## What it does?

Ghendetta is a foursquare extension. It's actualy sort of a game, to conquer a city in real-time. Users are able to login with their foursquare account. After that, they will be part of a 'clan' and their check-ins are like points for zones of the city. The team with the most points for a certain zone, owns it. The game goes on without an end, because check-ins are only valid for seven days. After that you might lose your zone.

## How to start?

### Get all files

* First get all source code in a directory and point your apache webserver to host this code.
* Put a standard CodeIgniter config.php file in the application/config folder
* Put a standard CodeIgniter database.php file in the application/config folder
* Adjust all needed configuration settings in those two files

### Register your application on foursquare

* Create a foursquare.php file in the application/config folder
* Go to <https://foursquare.com/oauth/> and register your application.
* Inside php tags (<?php ?>), enter $config['param'] = 'value'; for the following parameters:
** client: your registered application's clientid on foursquare
** secret: your registered application's secret on foursquare
** callback: http(s)://example.com/foursquare/callback ;

### Database

* fill your database with the script... Actualy, that's still to be determined if we're making that publicly available.

### Extra

* Be sure you have the php5-curl package installed.

# License

Ghendetta

Copyright © 2012 iRail vzw/asbl – Some rights reserved – AGPLv3

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.

If you want to use Ghendetta for your own city, country or game in general, please contact board@iRail.be.

# Authors

* Xavier Bertels – xavier at iRail.be
* Miet Claes – miet at iRail.be
* Hannes Van De Vreken – hannes at iRail.be
* Jens Segers – jens at iRail.be