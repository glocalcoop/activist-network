/*global google, jQuery*/
/*jslint browser: true, unparam: true*/
function replaceMap(e, map) {
    'use strict';
    map.mapTypes.set("OSM", new google.maps.ImageMapType({
        getTileUrl: function (coord, zoom) {
            return "https://a.tile.openstreetmap.org/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
        },
        tileSize: new google.maps.Size(256, 256),
        name: "OpenStreetMap",
        maxZoom: 18
    }));
    map.mapTypes.set("transport", new google.maps.ImageMapType({
        getTileUrl: function (coord, zoom) {
            return "https://a.tile.thunderforest.com/transport/" + zoom + "/" + coord.x + "/" + coord.y + ".png";
        },
        tileSize: new google.maps.Size(256, 256),
        name: "OSM Transport",
        maxZoom: 18
    }));
    map.mapTypeId = 'OSM';
    map.setOptions({
        mapTypeControl: true,
        mapTypeControlOptions: {
            mapTypeIds: ['OSM', 'transport']
        }

    });
    var copyright = document.createElement('div');
    copyright.innerHTML = '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors';
    copyright.style.backgroundColor = 'rgba(245, 245, 245, 0.7)';
    copyright.style.padding = '0 6px';
    copyright.style.fontSize = '10px';
    copyright.style.color = '#444';
    map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(copyright);
}

jQuery(document).bind('em_maps_location_hook', replaceMap);
