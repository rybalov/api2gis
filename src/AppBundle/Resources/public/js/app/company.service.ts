import { Company } from './company';
import { Building } from "./building";
import { Category } from "./category";
import { Injectable } from '@angular/core';
import { Http, Response, Headers, URLSearchParams } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';


@Injectable()
export class CompanyService {
    private baseUrl: string = '/api/search/companies';

    constructor(private http : Http){}

    findByBuilding(building: Building): Observable<Company[]> {
        let params = new URLSearchParams();

        params.set('type', 'address');
        params.set('city', building.address.city);
        params.set('street', building.address.street);
        params.set('house', building.address.house);

        return this.http
            .get(this.baseUrl, {headers: CompanyService.getHeaders(), search: params})
            .map(CompanyService.mapCompanies);
    }

    findByCategory(category: Category): Observable<Company[]> {
        let params = new URLSearchParams();
        params.set('type', 'category');
        params.set('category', category.id.toString());
        params.set('nested', '1');

        return this.http
            .get(this.baseUrl, {headers: CompanyService.getHeaders(), search: params})
            .map(CompanyService.mapCompanies);
    }

    find(name: string): Observable<Company[]> {
        let params = new URLSearchParams();

        if (name.length) {
            params.set('type', 'name');
            params.set('name', name);
        } else {
            params.set('type', 'all');
        }

        return this.http
            .get(this.baseUrl, {headers: CompanyService.getHeaders(), search: params})
            .map(CompanyService.mapCompanies);
    }

    findNearby(radius: number, lat: number, lon: number): Observable<Company[]> {
        let params = new URLSearchParams();

        params.set('type', 'radius');
        params.set('radius', radius.toString());
        params.set('lat', lat.toString());
        params.set('lon', lon.toString());

        return this.http
            .get(this.baseUrl, {headers: CompanyService.getHeaders(), search: params})
            .map(CompanyService.mapCompanies);
    }

    findWithinBound(lat1: number, lon1: number, lat2: number, lon2: number): Observable<Company[]> {
        let params = new URLSearchParams();

        params.set('type', 'bound');
        params.set('bound[lat1]', lat1.toString());
        params.set('bound[lon1]', lon1.toString());
        params.set('bound[lat2]', lat2.toString());
        params.set('bound[lon2]', lon2.toString());

        return this.http
            .get(this.baseUrl, {headers: CompanyService.getHeaders(), search: params})
            .map(CompanyService.mapCompanies);
    }

    private static getHeaders() {
        let headers = new Headers();
        headers.append('Accept', 'application/json');

        return headers;
    }

    private static mapCompanies(response:Response): Company[]{
        let results = response.json().result.map(CompanyService.toCompany);

        return results.length? results: [];
    }

    private static toCompany(r: any): Company{
        return <Company>({
            id:         r.id,
            name:       r.name,
            contacts:   r.contacts,
            address:    r.address,
            lat:        r.address && r.address.features?r.address.features[0].lat:null,
            lon:        r.address && r.address.features?r.address.features[0].lon:null
        });
    }
}
