import axios from 'axios';

export function getByName(
	name,
	limit = null,
	offset = null,
	axiosCancelToken = null
) {
	return axios({
		method: 'GET',
		url: '/api/items',
		responseType: 'json',
		params: {
			name,
			limit,
			offset,
		},
		cancelToken: axiosCancelToken
	});
}
