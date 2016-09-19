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
var building_service_1 = require('./building.service');
var company_service_1 = require('./company.service');
var category_service_1 = require('./category.service');
var AppComponent = (function () {
    function AppComponent(_buildingService, _companyService, _categoryService) {
        this._buildingService = _buildingService;
        this._companyService = _companyService;
        this._categoryService = _categoryService;
        this.buildingSearchStr = '';
        this.companySearchStr = '';
        this.categorySearchStr = '';
        this.buildings = [];
        this.companies = [];
        this.categories = [];
        this.companiesResults = [];
        this.boundPanMode = false;
        this.circlePanMode = false;
        this.showResultsMode = false;
        this.markerLayers = [];
    }
    AppComponent.prototype.ngOnInit = function () {
        this.initMap();
        this.initServices();
    };
    AppComponent.prototype.initMap = function () {
        this.map2gis = DG.map('map2gis', {
            center: [56.482615, 84.992681],
            zoom: 14,
            fullscreenControl: false
        });
        this.map2gis.zoomControl.setPosition('topright');
        this.map2gis.on('click', this.onMapClick, this);
    };
    AppComponent.prototype.initServices = function () {
        var _this = this;
        this._buildingService
            .find('')
            .subscribe(function (p) { return _this.buildings = p; });
        this._categoryService
            .find('')
            .subscribe(function (p) { return _this.categories = p; });
        this._companyService
            .find('')
            .subscribe(function (p) { return _this.companies = p; });
    };
    AppComponent.prototype.onMarkerClick = function () {
        var _this = this;
        this._companyService
            .findByBuilding(this.selectedBuilding)
            .subscribe(function (p) {
            _this.companiesResults = p;
            _this.showResultsMode = true;
        }, function (error) { return _this.companiesResults = []; });
    };
    AppComponent.prototype.onMapClick = function (e) {
        switch (true) {
            case this.circlePanMode:
                this.clearCircleLayer();
                this.clearMarkerLayers();
                this.circle = DG.circle([e.latlng.lat, e.latlng.lng], 500, { color: 'green', fillOpacity: 0.1 });
                this.circleLayer = this.circle.addTo(this.map2gis);
                this.loadCompaniesContainedInCircle();
                break;
            case this.boundPanMode:
                this.clearRectangleLayer();
                this.clearMarkerLayers();
                var rLat = ((0.5 + Math.random()) / 111) / 2;
                var rLon = ((0.5 + Math.random()) / 111) / 2;
                var bounds = [[e.latlng.lat + rLat, e.latlng.lng - rLon], [e.latlng.lat - rLat, e.latlng.lng + rLon]];
                this.rectangle = DG.rectangle(bounds, { color: "#ff7800", fillOpacity: 0.1 });
                this.rectangleLayer = this.rectangle.addTo(this.map2gis);
                this.loadCompaniesContainedInRectangle();
                break;
        }
    };
    AppComponent.prototype.selectBuilding = function (building) {
        this.selectedBuilding = building;
        var point = [building.lat, building.lon];
        var label = building.address.street + ', ' + building.address.house;
        if (!this.buildingMarker) {
            this.buildingMarker = DG.marker(point);
            this.buildingMarker.addTo(this.map2gis);
        }
        this.buildingMarker
            .setLatLng(point)
            .bindLabel(label, { 'static': true })
            .off('click', this.onMarkerClick, this)
            .on('click', this.onMarkerClick, this);
        this.map2gis.setView(point, 18);
    };
    AppComponent.prototype.selectCompany = function (company) {
        var point = [company.lat, company.lon];
        var content = '';
        if (this.buildingMarker) {
            this.buildingMarker.hideLabel();
        }
        if (!this.companyPopup || !this.companyPopup.isOpen()) {
            this.companyPopup = DG.popup({ minWidth: 300, maxWidth: 500 });
        }
        company.contacts.forEach(function (contact) {
            switch (true) {
                case !!contact.site:
                    content += ('Сайт: <a href="#" onclick="return false;">' + contact.site + '</a><br/>');
                    break;
                case !!contact.email:
                    content += ('E-mail: <a href="#" onclick="return false;">' + contact.email + '</a><br/>');
                    break;
                default:
                    content += ('Телефон: ' + contact.phone + '<br/>');
                    break;
            }
        });
        this.companyPopup
            .setLatLng(point)
            .setHeaderContent(company.name)
            .setContent('<p>' + content + '</p>')
            .setFooterContent('<br/><p>' + company.address.street + ', ' + company.address.house + '</p>')
            .openOn(this.map2gis);
        this.map2gis.setView(point, 17);
    };
    AppComponent.prototype.selectCategory = function (category) {
        var _this = this;
        this._companyService
            .findByCategory(category)
            .subscribe(function (p) {
            _this.companiesResults = p;
            _this.showResultsMode = true;
        });
    };
    AppComponent.prototype.onBuildingStrChange = function (event) {
        if (event.keyCode == 13) {
            this.searchBuilding();
        }
        this.buildingSearchStr = event.target.value;
    };
    AppComponent.prototype.onCompanyStrChange = function (event) {
        if (event.keyCode == 13) {
            this.searchCompany();
        }
        this.companySearchStr = event.target.value;
    };
    AppComponent.prototype.onCategoryStrChange = function (event) {
        if (event.keyCode == 13) {
            this.searchCategory();
        }
        this.categorySearchStr = event.target.value;
    };
    AppComponent.prototype.searchBuilding = function () {
        var _this = this;
        this._buildingService
            .find(this.buildingSearchStr)
            .subscribe(function (p) { return _this.buildings = p; }, function (error) { return _this.buildings = []; });
    };
    AppComponent.prototype.searchCompany = function () {
        var _this = this;
        this._companyService
            .find(this.companySearchStr)
            .subscribe(function (p) { return _this.companies = p; }, function (error) { return _this.companies = []; });
    };
    AppComponent.prototype.searchCategory = function () {
        var _this = this;
        this._categoryService
            .find(this.categorySearchStr)
            .subscribe(function (p) { return _this.categories = p; }, function (error) { return _this.companies = []; });
    };
    AppComponent.prototype.closeResults = function () {
        this.showResultsMode = false;
    };
    AppComponent.prototype.isBoundPanMode = function () {
        return this.boundPanMode;
    };
    AppComponent.prototype.isCirclePanMode = function () {
        return this.circlePanMode;
    };
    AppComponent.prototype.toggleBoundPanMode = function () {
        if (this.boundPanMode) {
            this.boundPanModeOff();
        }
        else {
            this.boundPanModeOn();
        }
        if (this.boundPanMode && this.circlePanMode) {
            this.circlePanModeOff();
        }
        if (this.boundPanMode) {
            this.boundPanModeOn();
        }
    };
    AppComponent.prototype.toggleCirclePanMode = function () {
        if (this.circlePanMode) {
            this.circlePanModeOff();
        }
        else {
            this.circlePanModeOn();
        }
        if (this.boundPanMode && this.circlePanMode) {
            this.boundPanModeOff();
        }
        if (this.circlePanMode) {
            this.circlePanModeOn();
        }
    };
    AppComponent.prototype.boundPanModeOn = function () {
        this.boundPanMode = true;
        this.clearBuildingMarker();
    };
    AppComponent.prototype.boundPanModeOff = function () {
        this.boundPanMode = false;
        this.clearRectangleLayer();
        this.clearMarkerLayers();
        this.clearBuildingMarker();
        this.clearCompanyPopup();
    };
    AppComponent.prototype.circlePanModeOn = function () {
        this.circlePanMode = true;
        this.clearBuildingMarker();
        this.clearCompanyPopup();
    };
    AppComponent.prototype.circlePanModeOff = function () {
        this.circlePanMode = false;
        this.clearCircleLayer();
        this.clearMarkerLayers();
        this.clearBuildingMarker();
        this.clearCompanyPopup();
    };
    AppComponent.prototype.clearCircleLayer = function () {
        if (this.circleLayer) {
            this.circleLayer.remove();
            this.circleLayer = null;
        }
    };
    AppComponent.prototype.clearRectangleLayer = function () {
        if (this.rectangleLayer) {
            this.rectangleLayer.remove();
            this.rectangleLayer = null;
        }
    };
    AppComponent.prototype.clearMarkerLayers = function () {
        var _this = this;
        this.markerLayers.forEach(function (layer) {
            _this.map2gis.removeLayer(layer);
        }, this);
    };
    AppComponent.prototype.clearBuildingMarker = function () {
        if (this.buildingMarker) {
            this.buildingMarker.remove();
            this.buildingMarker = null;
        }
    };
    AppComponent.prototype.clearCompanyPopup = function () {
        if (this.companyPopup) {
            this.companyPopup.remove();
            this.companyPopup = null;
        }
    };
    AppComponent.prototype.loadCompaniesContainedInCircle = function () {
        var _this = this;
        var radius = this.circle.getRadius();
        var latLng = this.circle.getLatLng();
        var lat = latLng.lat;
        var lon = latLng.lng;
        this._companyService
            .findNearby(radius, lat, lon)
            .subscribe(function (p) {
            _this.companiesResults = p;
            _this.showResultsMode = true;
            _this.showCompaniesOnMap();
            _this.map2gis.setView(latLng, 16);
        }, function (error) { return _this.companiesResults = []; });
    };
    AppComponent.prototype.loadCompaniesContainedInRectangle = function () {
        var _this = this;
        var bounds = this.rectangle.getBounds();
        var lat1 = bounds.getNorthWest().lat;
        var lon1 = bounds.getNorthWest().lng;
        var lat2 = bounds.getSouthEast().lat;
        var lon2 = bounds.getSouthEast().lng;
        this._companyService
            .findWithinBound(lat1, lon1, lat2, lon2)
            .subscribe(function (p) {
            _this.companiesResults = p;
            _this.showResultsMode = true;
            _this.showCompaniesOnMap();
            _this.map2gis.fitBounds(bounds);
        }, function (error) { return _this.companiesResults = []; });
    };
    AppComponent.prototype.showCompaniesOnMap = function () {
        var _this = this;
        this.companiesResults.forEach(function (company) {
            var point = [company.lat, company.lon];
            var marker = DG
                .marker(point)
                .on('click', function () { return _this.selectCompany(company); }, _this)
                .addTo(_this.map2gis);
            _this.markerLayers.push(marker);
        }, this);
    };
    AppComponent = __decorate([
        core_1.Component({
            selector: 'gis-panel',
            providers: [building_service_1.BuildingService, company_service_1.CompanyService, category_service_1.CategoryService],
            template: "\n<div id=\"map2gis\"></div>\n<div class=\"panel\">  \n    <ul class=\"nav nav-tabs\" role=\"tablist\">\n        <li class=\"nav-item\">\n            <a class=\"nav-link active\" data-toggle=\"tab\" role=\"tab\" href=\"#buildings\"><i class=\"fa fa-building-o\" aria-hidden=\"true\"></i>\u0417\u0434\u0430\u043D\u0438\u044F</a>\n        </li>\n        <li class=\"nav-item\">\n            <a class=\"nav-link\" data-toggle=\"tab\" role=\"tab\" href=\"#categories\"><i class=\"fa fa-tags\" aria-hidden=\"true\"></i>\u0420\u0443\u0431\u0440\u0438\u043A\u0438</a>\n        </li>\n        <li class=\"nav-item\">\n            <a class=\"nav-link\" data-toggle=\"tab\" role=\"tab\" href=\"#companies\"><i class=\"fa fa-building\" aria-hidden=\"true\"></i>\u041A\u043E\u043C\u043F\u0430\u043D\u0438\u0438</a>\n        </li>\n    </ul>\n    <div class=\"tab-content\">\n        <div class=\"tab-pane fade in active\" id=\"buildings\" role=\"tabpanel\">\n            <form onsubmit=\"return false\">\n                <div class=\"input-group\">\n                    <input type=\"text\" class=\"form-control\" placeholder=\"\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0430\u0434\u0440\u0435\u0441\" (keyup)=\"onBuildingStrChange($event)\">\n                    <span class=\"input-group-btn\">\n                        <button class=\"btn btn-primary\" type=\"button\" (click)=\"searchBuilding()\"><i class=\"fa fa-search\" aria-hidden=\"true\"></i></button>\n                    </span>\n                </div>\n            </form>\n            <div class=\"list\">\n                <ul class=\"list-unstyled\">\n                    <li *ngFor=\"let building of buildings\">\n                        <a href=\"#\" (click)=\"selectBuilding(building)\" onclick=\"return false;\">{{building.address.street}}, {{building.address.house}}</a>\n                    </li>\n                </ul>\n            </div>\n        </div>\n        <div class=\"tab-pane fade\" id=\"companies\" role=\"tabpanel\">\n            <form onsubmit=\"return false\">\n                <div class=\"input-group\">\n                    <input type=\"text\" class=\"form-control\" placeholder=\"\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u043D\u0430\u0437\u0432\u0430\u043D\u0438\u0435\" (keyup)=\"onCompanyStrChange($event)\">\n                    <span class=\"input-group-btn\">\n                        <button class=\"btn btn-primary\" type=\"button\" (click)=\"searchCompany()\"><i class=\"fa fa-search\" aria-hidden=\"true\"></i></button>\n                    </span>\n                </div>\n            </form>\n            <div class=\"list\">\n                <ul class=\"list-unstyled\">\n                    <li *ngFor=\"let company of companies\">\n                        <a href=\"#\" (click)=\"selectCompany(company)\" onclick=\"return false;\">{{company.name}}</a>\n                    </li>\n                </ul>\n            </div>\n        </div>\n        <div class=\"tab-pane fade\" id=\"categories\" role=\"tabpanel\">\n            <form onsubmit=\"return false\">\n                <div class=\"input-group\">\n                    <input type=\"text\" class=\"form-control\" placeholder=\"\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u043D\u0430\u0437\u0432\u0430\u043D\u0438\u0435\" (keyup)=\"onCategoryStrChange($event)\">\n                    <span class=\"input-group-btn\">\n                        <button class=\"btn btn-primary\" type=\"button\"><i class=\"fa fa-search\" aria-hidden=\"true\"></i></button>\n                    </span>\n                </div>\n            </form>\n            <div class=\"list\">\n                <ul class=\"list-unstyled\">\n                    <li *ngFor=\"let category of categories\" class=\"lvl-{{category.level}}\">\n                        <a href=\"#\" (click)=\"selectCategory(category)\" onclick=\"return false;\">\n                            <i *ngIf=\"category.level > 1\" class=\"fa fa-angle-right\" aria-hidden=\"true\"></i> {{category.name}}\n                        </a>\n                    </li>\n                </ul>\n            </div>\n        </div>\n    </div>\n</div>\n<div *ngIf=\"showResultsMode == true\" class=\"panel-results\">\n    <ul class=\"nav nav-tabs\" role=\"tablist\">\n        <li class=\"nav-item\">\n            <a class=\"nav-link active\" data-toggle=\"tab\" role=\"tab\" href=\"#companies-results\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i>\u0421\u043F\u0438\u0441\u043E\u043A \u043A\u043E\u043C\u043F\u0430\u043D\u0438\u0439</a>\n        </li>\n    </ul>\n    <div class=\"tab-content\">\n        <div class=\"tab-pane fade in active\" id=\"companies-results\" role=\"tabpanel\">\n            <div class=\"list\">\n                <ul class=\"list-unstyled\">\n                    <li *ngFor=\"let company of companiesResults\">\n                        <a href=\"#\" (click)=\"selectCompany(company)\" onclick=\"return false;\">{{company.name}}</a>\n                    </li>\n                </ul>\n            </div>\n        </div>\n    </div>\n    <button class=\"btn btn-primary\" type=\"button\" (click)=\"closeResults()\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i></button>\n</div>\n<div class=\"panel-spatial\">\n    <ul class=\"nav nav-tabs\" role=\"tablist\">\n        <li class=\"nav-item\">\n            <a class=\"nav-link active\" data-toggle=\"tab\" role=\"tab\" href=\"#companies-results\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i>\u0412\u044B\u0434\u0435\u043B\u0438\u0442\u044C \u043E\u0431\u043B\u0430\u0441\u0442\u044C</a>\n        </li>\n    </ul>\n    <div class=\"btn-block\">\n        <button class=\"btn btn-primary\" type=\"button\" (click)=\"toggleBoundPanMode()\" [class.active]=\"boundPanMode\"><i class=\"fa fa-square-o\" aria-hidden=\"true\"></i></button>\n        <div class=\"tooltip tooltip-left\" role=\"tooltip\">\n          <div class=\"tooltip-arrow\"></div>\n          <div class=\"tooltip-inner\">\n            \u0427\u0442\u043E\u0431\u044B \u0437\u0430\u0434\u0430\u0442\u044C \u043E\u0431\u043B\u0430\u0441\u0442\u044C \u043F\u043E\u0438\u0441\u043A\u0430, \u043A\u043B\u0438\u043A\u043D\u0438\u0442\u0435 \u043F\u043E \u043A\u0430\u0440\u0442\u0435\n          </div>\n        </div>\n    </div>\n    <div class=\"btn-block\">\n        <button class=\"btn btn-second\" type=\"button\" (click)=\"toggleCirclePanMode()\" [class.active]=\"circlePanMode\"><i class=\"fa fa-circle-thin\" aria-hidden=\"true\"></i></button>\n        <div class=\"tooltip tooltip-left\" role=\"tooltip\">\n          <div class=\"tooltip-arrow\"></div>\n          <div class=\"tooltip-inner\">\n            \u0427\u0442\u043E\u0431\u044B \u0437\u0430\u0434\u0430\u0442\u044C \u043E\u0431\u043B\u0430\u0441\u0442\u044C \u043F\u043E\u0438\u0441\u043A\u0430, \u043A\u043B\u0438\u043A\u043D\u0438\u0442\u0435 \u043F\u043E \u043A\u0430\u0440\u0442\u0435\n          </div>\n        </div>\n    </div>\n</div>\n"
        }), 
        __metadata('design:paramtypes', [building_service_1.BuildingService, company_service_1.CompanyService, category_service_1.CategoryService])
    ], AppComponent);
    return AppComponent;
}());
exports.AppComponent = AppComponent;
//# sourceMappingURL=app.component.js.map