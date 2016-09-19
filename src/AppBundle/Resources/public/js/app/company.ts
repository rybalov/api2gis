import { Contact } from './contact';

export interface Company {
    id:         number;
    name:       string;
    contacts:   Contact[];
    address: {
        city:       string;
        street:     string;
        house:      string;
        postcode:   string;
    };
    lat: number;
    lon: number;
}
