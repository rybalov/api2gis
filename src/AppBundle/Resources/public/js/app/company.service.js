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
var CompanyService = (function () {
    function CompanyService(http) {
        this.http = http;
        this.baseUrl = '/api/search/companies';
    }
    CompanyService.prototype.findByBuilding = function (building) {
        var params = new http_1.URLSearchParams();
        params.set('type', 'address');
        params.set('city', building.address.city);
        params.set('street', building.address.street);
        params.set('house', building.address.house);
        return this.http
            .get(this.baseUrl, { headers: CompanyService.getHeaders(), search: params })
            .map(CompanyService.mapCompanies);
    };
    CompanyService.prototype.findByCategory = function (category) {
        var params = new http_1.URLSearchParams();
        params.set('type', 'category');
        params.set('category', category.id.toString());
        params.set('nested', '1');
        return this.http
            .get(this.baseUrl, { headers: CompanyService.getHeaders(), search: params })
            .map(CompanyService.mapCompanies);
    };
    CompanyService.prototype.find = function (name) {
        var params = new http_1.URLSearchParams();
        if (name.length) {
            params.set('type', 'name');
            params.set('name', name);
        }
        else {
            params.set('type', 'all');
        }
        return this.http
            .get(this.baseUrl, { headers: CompanyService.getHeaders(), search: params })
            .map(CompanyService.mapCompanies);
    };
    CompanyService.prototype.findNearby = function (radius, lat, lon) {
        var params = new http_1.URLSearchParams();
        params.set('type', 'radius');
        params.set('radius', radius.toString());
        params.set('lat', lat.toString());
        params.set('lon', lon.toString());
        return this.http
            .get(this.baseUrl, { headers: CompanyService.getHeaders(), search: params })
            .map(CompanyService.mapCompanies);
    };
    CompanyService.prototype.findWithinBound = function (lat1, lon1, lat2, lon2) {
        var params = new http_1.URLSearchParams();
        params.set('type', 'bound');
        params.set('bound[lat1]', lat1.toString());
        params.set('bound[lon1]', lon1.toString());
        params.set('bound[lat2]', lat2.toString());
        params.set('bound[lon2]', lon2.toString());
        return this.http
            .get(this.baseUrl, { headers: CompanyService.getHeaders(), search: params })
            .map(CompanyService.mapCompanies);
    };
    CompanyService.getHeaders = function () {
        var headers = new http_1.Headers();
        headers.append('Accept', 'application/json');
        return headers;
    };
    CompanyService.mapCompanies = function (response) {
        var results = response.json().result.map(CompanyService.toCompany);
        return results.length ? results : [];
    };
    CompanyService.toCompany = function (r) {
        return ({
            id: r.id,
            name: r.name,
            contacts: r.contacts,
            address: r.address,
            lat: r.address && r.address.features ? r.address.features[0].lat : null,
            lon: r.address && r.address.features ? r.address.features[0].lon : null
        });
    };
    CompanyService = __decorate([
        core_1.Injectable(), 
        __metadata('design:paramtypes', [http_1.Http])
    ], CompanyService);
    return CompanyService;
}());
exports.CompanyService = CompanyService;
//# sourceMappingURL=company.service.js.map