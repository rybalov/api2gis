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
var CategoryService = (function () {
    function CategoryService(http) {
        this.http = http;
        this.baseUrl = '/api/search/categories';
    }
    CategoryService.prototype.find = function (name) {
        var params = new http_1.URLSearchParams();
        if (name.length) {
            params.set('type', 'name');
            params.set('name', name);
        }
        else {
            params.set('type', 'all');
        }
        return this.http
            .get(this.baseUrl, { headers: CategoryService.getHeaders(), search: params })
            .map(CategoryService.mapCategories);
    };
    CategoryService.getHeaders = function () {
        var headers = new http_1.Headers();
        headers.append('Accept', 'application/json');
        return headers;
    };
    CategoryService.mapCategories = function (response) {
        var results = response.json().result.map(CategoryService.toCategory);
        return results.length ? results : [];
    };
    CategoryService.toCategory = function (r) {
        return ({
            id: r.id,
            name: r.name,
            level: r.lvl
        });
    };
    CategoryService = __decorate([
        core_1.Injectable(), 
        __metadata('design:paramtypes', [http_1.Http])
    ], CategoryService);
    return CategoryService;
}());
exports.CategoryService = CategoryService;
//# sourceMappingURL=category.service.js.map