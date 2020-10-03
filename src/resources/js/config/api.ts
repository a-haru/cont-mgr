import axios, { AxiosResponse } from 'axios';

export const endpoints = {
    getClients: '/api/clients',
    getClient: 'api/client',
    deleteClient: '/api/client'
}

type ClientId = {
    id: number;
};

type StoreClient = {
    name: string;
    url: string;
    contractActivateAt: string;
    contractDeactivateAt: string;
}

export type Client = ClientId & StoreClient;

export const ClientInit: Client = {
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

export function deleteClient(id: number): Promise<AxiosResponse<boolean>>
{
    const params = {id};
    return axios.delete(endpoints.deleteClient, {
        params
    });
}

// export function storeClient(data: Client)

export type ContentData = {
    title: string;
    description: string;
    text: string;
    note: string;
}

export const InitContentData: ContentData = {
    title: '',
    description: '',
    text: '',
    note: ''
}

export function storeContent(id: number, data: ContentData): Promise<AxiosResponse<boolean>>
{
    const params = new URLSearchParams(data);
    return axios.post(`/api/content/${id}`, params);
}

export function fetchAutosaveContent(clientId: number, contentId: number): Promise<AxiosResponse<ContentData>>
{
    return axios.get(`/api/content/autosave/${clientId}/${contentId}`, {
        responseType: 'json'
    });
}

export function automaticallySaveContent(data: ContentData, clientId: number, contentId: number): Promise<AxiosResponse<boolean>>
{
    const params = new URLSearchParams(data);
    return axios.post(`/api/content/autosave/${clientId}/${contentId}`, params);
}
