import axios, { AxiosResponse } from 'axios';

export const endpoints = {
    getClients: '/api/clients',
    getClient: 'api/client',
    deleteClient: '/api/client'
}

type ClientId = {
    id: number;
};

export type StoreClient = {
    name: string;
    url: string;
    contract_activate_at: string;
    contract_deactivate_at: string;
}

export type Client = ClientId & StoreClient;

export const ClientInit: Client = {
    id: 0,
    name: '',
    url: '',
    contract_activate_at: '',
    contract_deactivate_at: ''
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

export function storeClient(data: StoreClient): Promise<AxiosResponse<boolean>>
{
    return axios.post('/api/client', data);
}

export type ContentId = {
    id: number;
    client_id: number;
}

export type ContentData = {
    title: string;
    description: string;
    text: string;
    note: string;
}

export type Content = ContentId & ContentData;

export const InitContentData: ContentData = {
    title: '',
    description: '',
    text: '',
    note: ''
}

export function getContents(clientId: number): Promise<AxiosResponse<Content[]>>
{
    return axios.get(`/api/contents/${clientId}`)
}

export function storeContent(id: number, data: ContentData): Promise<AxiosResponse<boolean>>
{
    const params = new URLSearchParams(data);
    return axios.post(`/api/content/${id}`, params);
}

export function fetchContent(contentId: number): Promise<AxiosResponse<Content>>
{
    return axios.get(`/api/content/${contentId}`, {
        responseType: 'json'
    });
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
