"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
var core_1 = require('@angular/core');
var http_1 = require('@angular/http');
require('rxjs/add/operator/map');
var BuildingService = (function () {
    function BuildingService(http) {
        this.http = http;
        this.baseUrl = '/api/search/buildings';
    }
    BuildingService.prototype.find = function (street) {
        var params = new http_1.URLSearchParams();
        if (street.length) {
            params.set('type', 'street');
            params.set('street', street);
        }
        else {
            params.set('type', 'all');
        }
        return this.http
            .get(this.baseUrl, { headers: BuildingService.getHeaders(), search: params })
            .map(BuildingService.mapBuildings);
    };
    BuildingService.getHeaders = function () {
        var headers = new http_1.Headers();
        headers.append('Accept', 'application/json');
        return headers;
    };
    BuildingService.mapBuildings = function (response) {
        return response.json().result.map(BuildingService.toBuilding);
    };
    BuildingService.toBuilding = function (r) {
        return ({
            address: r.addresses[0],
            lat: r.lat,
            lon: r.lon
        });
    };
    BuildingService = __decorate([
        core_1.Injectable(), 
        __metadata('design:paramtypes', [http_1.Http])
    ], BuildingService);
    return BuildingService;
}());
exports.BuildingService = BuildingService;
//# sourceMappingURL=building.service.js.map