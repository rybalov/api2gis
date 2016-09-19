import { Component, OnInit } from '@angular/core';
import { Building } from './building';
import { Company } from './company';
import { Category } from './category';
import { BuildingService } from './building.service';
import { CompanyService } from './company.service';
import { CategoryService } from './category.service';

declare var DG: any;

@Component({
    selector: 'gis-panel',
    providers: [BuildingService, CompanyService, CategoryService],
    template: `
<div id="map2gis"></div>
<div class="panel">  
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" role="tab" href="#buildings"><i class="fa fa-building-o" aria-hidden="true"></i>Здания</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" role="tab" href="#categories"><i class="fa fa-tags" aria-hidden="true"></i>Рубрики</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" role="tab" href="#companies"><i class="fa fa-building" aria-hidden="true"></i>Компании</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="buildings" role="tabpanel">
            <form onsubmit="return false">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Введите адрес" (keyup)="onBuildingStrChange($event)">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" (click)="searchBuilding()"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </span>
                </div>
            </form>
            <div class="list">
                <ul class="list-unstyled">
                    <li *ngFor="let building of buildings">
                        <a href="#" (click)="selectBuilding(building)" onclick="return false;">{{building.address.street}}, {{building.address.house}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-pane fade" id="companies" role="tabpanel">
            <form onsubmit="return false">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Введите название" (keyup)="onCompanyStrChange($event)">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" (click)="searchCompany()"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </span>
                </div>
            </form>
            <div class="list">
                <ul class="list-unstyled">
                    <li *ngFor="let company of companies">
                        <a href="#" (click)="selectCompany(company)" onclick="return false;">{{company.name}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-pane fade" id="categories" role="tabpanel">
            <form onsubmit="return false">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Введите название" (keyup)="onCategoryStrChange($event)">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </span>
                </div>
            </form>
            <div class="list">
                <ul class="list-unstyled">
                    <li *ngFor="let category of categories" class="lvl-{{category.level}}">
                        <a href="#" (click)="selectCategory(category)" onclick="return false;">
                            <i *ngIf="category.level > 1" class="fa fa-angle-right" aria-hidden="true"></i> {{category.name}}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div *ngIf="showResultsMode == true" class="panel-results">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" role="tab" href="#companies-results"><i class="fa fa-map-marker" aria-hidden="true"></i>Список компаний</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="companies-results" role="tabpanel">
            <div class="list">
                <ul class="list-unstyled">
                    <li *ngFor="let company of companiesResults">
                        <a href="#" (click)="selectCompany(company)" onclick="return false;">{{company.name}}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <button class="btn btn-primary" type="button" (click)="closeResults()"><i class="fa fa-times" aria-hidden="true"></i></button>
</div>
<div class="panel-spatial">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" role="tab" href="#companies-results"><i class="fa fa-map-marker" aria-hidden="true"></i>Выделить область</a>
        </li>
    </ul>
    <div class="btn-block">
        <button class="btn btn-primary" type="button" (click)="toggleBoundPanMode()" [class.active]="boundPanMode"><i class="fa fa-square-o" aria-hidden="true"></i></button>
        <div class="tooltip tooltip-left" role="tooltip">
          <div class="tooltip-arrow"></div>
          <div class="tooltip-inner">
            Чтобы задать область поиска, кликните по карте
          </div>
        </div>
    </div>
    <div class="btn-block">
        <button class="btn btn-second" type="button" (click)="toggleCirclePanMode()" [class.active]="circlePanMode"><i class="fa fa-circle-thin" aria-hidden="true"></i></button>
        <div class="tooltip tooltip-left" role="tooltip">
          <div class="tooltip-arrow"></div>
          <div class="tooltip-inner">
            Чтобы задать область поиска, кликните по карте
          </div>
        </div>
    </div>
</div>
`
})

export class AppComponent implements OnInit {
    buildingSearchStr:  string = '';
    companySearchStr:   string = '';
    categorySearchStr:  string = '';

    selectedBuilding:   Building;

    buildings:          Building[]  = [];
    companies:          Company[]   = [];
    categories:         Category[]  = [];
    companiesResults:   Company[]   = [];

    boundPanMode:       boolean = false;
    circlePanMode:      boolean = false;
    showResultsMode:    boolean = false;

    map2gis:            any;
    buildingMarker:     any;
    companyPopup:       any;
    circleLayer:        any;
    circle:             any;
    rectangleLayer:     any;
    rectangle:          any;
    markerLayers:       any[] = [];

    constructor(private _buildingService:   BuildingService,
                private _companyService:    CompanyService,
                private _categoryService:   CategoryService){}

    ngOnInit() {
        this.initMap();
        this.initServices();
    }

    initMap() {
        this.map2gis = DG.map('map2gis', {
            center: [56.482615, 84.992681],
            zoom: 14,
            fullscreenControl: false
        });
        this.map2gis.zoomControl.setPosition('topright');
        this.map2gis.on('click', this.onMapClick, this);
    }

    initServices() {
        this._buildingService
            .find('')
            .subscribe(p => this.buildings = p);

        this._categoryService
            .find('')
            .subscribe(p => this.categories = p);

        this._companyService
            .find('')
            .subscribe(p => this.companies = p);
    }

    onMarkerClick() {
        this._companyService
            .findByBuilding(this.selectedBuilding)
            .subscribe(
                p => {
                    this.companiesResults = p;
                    this.showResultsMode = true;
                },
                error => this.companiesResults = []
            );
    }

    onMapClick(e: any) {
        switch (true) {
            case this.circlePanMode:
                this.clearCircleLayer();
                this.clearMarkerLayers();

                this.circle         = DG.circle([e.latlng.lat, e.latlng.lng], 500, {color: 'green', fillOpacity: 0.1});
                this.circleLayer    = this.circle.addTo(this.map2gis);

                this.loadCompaniesContainedInCircle();
                break;
            case this.boundPanMode:
                this.clearRectangleLayer();
                this.clearMarkerLayers();

                var rLat    = ((0.5 + Math.random()) / 111) / 2;
                var rLon    = ((0.5 + Math.random()) / 111) / 2;
                var bounds  = [[e.latlng.lat + rLat, e.latlng.lng - rLon], [e.latlng.lat - rLat, e.latlng.lng + rLon]];

                this.rectangle      = DG.rectangle(bounds, {color: "#ff7800", fillOpacity: 0.1});
                this.rectangleLayer = this.rectangle.addTo(this.map2gis);

                this.loadCompaniesContainedInRectangle();
                break;
        }
    }

    selectBuilding(building: Building) {
        this.selectedBuilding = building;

        let point = [building.lat, building.lon];
        let label = building.address.street + ', ' + building.address.house;

        if (!this.buildingMarker) {
            this.buildingMarker = DG.marker(point);
            this.buildingMarker.addTo(this.map2gis);
        }

        this.buildingMarker
            .setLatLng(point)
            .bindLabel(label, {'static': true})
            .off('click', this.onMarkerClick, this)
            .on('click', this.onMarkerClick, this);

        this.map2gis.setView(point, 18);
    }

    selectCompany(company: Company) {
        let point   = [company.lat, company.lon];
        let content = '';

        if (this.buildingMarker) {
            this.buildingMarker.hideLabel();
        }

        if (!this.companyPopup || !this.companyPopup.isOpen()) {
            this.companyPopup = DG.popup({minWidth: 300, maxWidth: 500})
        }

        company.contacts.forEach(contact => {
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
    }

    selectCategory(category: Category) {
        this._companyService
            .findByCategory(category)
            .subscribe(p => {
                this.companiesResults = p;
                this.showResultsMode = true;
            });
    }

    onBuildingStrChange(event: any) {
        if (event.keyCode == 13) {
            this.searchBuilding();
        }

        this.buildingSearchStr = event.target.value;
    }

    onCompanyStrChange(event: any) {
        if (event.keyCode == 13) {
            this.searchCompany();
        }

        this.companySearchStr = event.target.value;
    }

    onCategoryStrChange(event: any) {
        if (event.keyCode == 13) {
            this.searchCategory();
        }

        this.categorySearchStr = event.target.value;
    }

    searchBuilding() {
        this._buildingService
            .find(this.buildingSearchStr)
            .subscribe(p => this.buildings = p, error => this.buildings = []);
    }

    searchCompany() {
        this._companyService
            .find(this.companySearchStr)
            .subscribe(p => this.companies = p, error => this.companies = []);
    }

    searchCategory() {
        this._categoryService
            .find(this.categorySearchStr)
            .subscribe(p => this.categories = p, error => this.companies = []);
    }

    closeResults() {
        this.showResultsMode = false;
    }

    isBoundPanMode() {
        return this.boundPanMode;
    }

    isCirclePanMode() {
        return this.circlePanMode;
    }

    toggleBoundPanMode() {
        if (this.boundPanMode) {
            this.boundPanModeOff();
        } else {
            this.boundPanModeOn();
        }

        if (this.boundPanMode && this.circlePanMode) {
            this.circlePanModeOff();
        }

        if (this.boundPanMode) {
            this.boundPanModeOn();
        }
    }

    toggleCirclePanMode() {
        if (this.circlePanMode) {
            this.circlePanModeOff();
        } else {
            this.circlePanModeOn();
        }

        if (this.boundPanMode && this.circlePanMode) {
            this.boundPanModeOff();
        }

        if (this.circlePanMode) {
            this.circlePanModeOn();
        }
    }

    boundPanModeOn() {
        this.boundPanMode = true;
        this.clearBuildingMarker();
    }

    boundPanModeOff() {
        this.boundPanMode = false;
        this.clearRectangleLayer();
        this.clearMarkerLayers();
        this.clearBuildingMarker();
        this.clearCompanyPopup();
    }

    circlePanModeOn() {
        this.circlePanMode = true;
        this.clearBuildingMarker();
        this.clearCompanyPopup();
    }

    circlePanModeOff() {
        this.circlePanMode = false;
        this.clearCircleLayer();
        this.clearMarkerLayers();
        this.clearBuildingMarker();
        this.clearCompanyPopup();
    }

    clearCircleLayer() {
        if (this.circleLayer) {
            this.circleLayer.remove();
            this.circleLayer = null;
        }
    }

    clearRectangleLayer() {
        if (this.rectangleLayer) {
            this.rectangleLayer.remove();
            this.rectangleLayer = null;
        }
    }

    clearMarkerLayers() {
        this.markerLayers.forEach(layer => {
            this.map2gis.removeLayer(layer);
        }, this);
    }

    clearBuildingMarker() {
        if (this.buildingMarker) {
            this.buildingMarker.remove();
            this.buildingMarker = null;
        }
    }

    clearCompanyPopup() {
        if (this.companyPopup) {
            this.companyPopup.remove();
            this.companyPopup = null;
        }
    }

    loadCompaniesContainedInCircle() {
        let radius  = this.circle.getRadius();
        let latLng  = this.circle.getLatLng();
        let lat     = latLng.lat;
        let lon     = latLng.lng;

        this._companyService
            .findNearby(radius, lat, lon)
            .subscribe(
                p => {
                    this.companiesResults = p;
                    this.showResultsMode = true;
                    this.showCompaniesOnMap();
                    this.map2gis.setView(latLng, 16);
                },
                error => this.companiesResults = []
            );
    }

    loadCompaniesContainedInRectangle() {
        let bounds  = this.rectangle.getBounds();
        let lat1    = bounds.getNorthWest().lat;
        let lon1    = bounds.getNorthWest().lng;
        let lat2    = bounds.getSouthEast().lat;
        let lon2    = bounds.getSouthEast().lng;

        this._companyService
            .findWithinBound(lat1, lon1, lat2, lon2)
            .subscribe(
                p => {
                    this.companiesResults = p;
                    this.showResultsMode = true;
                    this.showCompaniesOnMap();
                    this.map2gis.fitBounds(bounds);
                },
                error => this.companiesResults = []
            );
    }

    showCompaniesOnMap() {
        this.companiesResults.forEach(company => {
            let point   = [company.lat, company.lon];
            let marker  = DG
                .marker(point)
                .on('click', () => this.selectCompany(company), this)
                .addTo(this.map2gis);
            this.markerLayers.push(marker);
        }, this);
    }
}
