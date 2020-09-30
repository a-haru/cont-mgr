import axios, { AxiosResponse } from 'axios';

export const endpoints = {
    getClients: '/api/clients',
    getClient: 'api/client'
}

export type Client = {
    id: number;
    name: string;
    url: string;
    contractActivateAt: string;
    contractDeactivateAt: string;
};

export const CleintInit: Client = {
    id: 0,
    name: '',
    url: '',
    contractActivateAt: '',
    contractDeactivateAt: ''
};

export function getCleints(): Promise<AxiosResponse<Client[]>>
{
    return axios.get(endpoints.getClients);
};

export function getCleint(id?: number): Promise<AxiosResponse<Client>>
{
    const params = id !== undefined ? {id} : {};
    return axios.get(endpoints.getClient, {
        params
    });
};
