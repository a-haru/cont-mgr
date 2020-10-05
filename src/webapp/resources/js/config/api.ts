import axios, { AxiosResponse } from 'axios';

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

export function fetchClients(): Promise<AxiosResponse<Client[]>>
{
    return axios.get('/api/clients');
};

export function storeClient(data: StoreClient): Promise<AxiosResponse<boolean>>
{
    return axios.post('/api/clients', data);
};

export function fetchClient(clientId?: number): Promise<AxiosResponse<Client>>
{
    return axios.get(`/api/clients/${clientId}`);
};

export function deleteClient(clientId: number): Promise<AxiosResponse<boolean>>
{
    return axios.delete(`/api/clients/${clientId}`);
}

export function updateClient(clientId: number, data: StoreClient)
{
    return axios.patch(`/api/clients/${clientId}`, data);
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

export function storeContent(clientId: number, data: ContentData): Promise<AxiosResponse<boolean>>
{
    const params = new URLSearchParams(data);
    return axios.post(`/api/contents/${clientId}`, params);
}

export function updateContent(clientId: number, contentId: number, data: ContentData): Promise<AxiosResponse<boolean>>
{
    const params = new URLSearchParams(data);
    return axios.patch(`/api/contents/${clientId}/${contentId}`, params);
}

export function fetchContent(clientId: number, contentId: number): Promise<AxiosResponse<Content>>
{
    return axios.get(`/api/contents/${clientId}/${contentId}`, {
        responseType: 'json'
    });
}

export function deleteContent(clientId: number, contentId: number): Promise<AxiosResponse<boolean>>
{
    return axios.delete(`/api/contents/${clientId}/${contentId}`, {
        responseType: 'json'
    });
}

export function fetchAutosaveContent(clientId: number, contentId: number): Promise<AxiosResponse<ContentData>>
{
    return axios.get(`/api/contents/autosave/${clientId}/${contentId}`, {
        responseType: 'json'
    });
}

export function storeAutosaveContent(data: ContentData, clientId: number, contentId: number): Promise<AxiosResponse<boolean>>
{
    const params = new URLSearchParams(data);
    return axios.post(`/api/contents/autosave/${clientId}/${contentId}`, params);
}
