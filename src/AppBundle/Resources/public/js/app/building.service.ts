import { Building } from './building';
import { Injectable } from '@angular/core';
import { Http, Response, Headers, URLSearchParams } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';


@Injectable()
export class BuildingService {
    private baseUrl: string = '/api/search/buildings';

    constructor(private http : Http){}

    find(street: string): Observable<Building[]> {
        let params = new URLSearchParams();

        if (street.length) {
            params.set('type', 'street');
            params.set('street', street);
        } else {
            params.set('type', 'all');
        }

        return this.http
            .get(this.baseUrl, {headers: BuildingService.getHeaders(), search: params})
            .map(BuildingService.mapBuildings);
    }

    private static getHeaders() {
        let headers = new Headers();
        headers.append('Accept', 'application/json');

        return headers;
    }

    private static mapBuildings(response: Response): Building[]{
        return response.json().result.map(BuildingService.toBuilding);
    }

    private static toBuilding(r: any): Building{
        return <Building>({
            address:    r.addresses[0],
            lat:        r.lat,
            lon:        r.lon
        });
    }
}

