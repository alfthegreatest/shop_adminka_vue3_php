import axios from 'axios';
import _ from 'lodash';

const state = {
    all: []
}

const getters = {}

const mutations = {
    SET_BRANDS (state, brands) {
        state.all = brands
    },
    ADD_BRAND (state, brand) {
        state.all.push(brand);
    },
    UPDATE_BRAND (state, payload) {
        let brand = _.find(state.all, { id: payload.id });
        brand.title = payload.title;
    },
    REMOVE_BRAND (state, brandId) {
        state.all = state.all.filter(brand => brand.id !== brandId);
    }
}

const actions = {
    getBrands (context) {
        axios
            .get('/brands')
            .then(response => {
                context.commit('SET_BRANDS', response.data.records)
            })
    },
    addBrand (context, newBrand) {
        const data = 'title=' + newBrand;

        return new Promise ((resolve, reject) => {
            axios
                .post('/brands', data)
                .then(response => {
                    context.commit('ADD_BRAND', response.data);
                    resolve(response);
                }, error => {
                    reject(error);
                });
        });
    },
    updateBrand (context, payload) {
        const data = 'title=' + payload.title;

        axios
            .put('/brands/' + payload.id, data)
            .then(response => {
                context.commit('UPDATE_BRAND', payload)
            });
    },
    removeBrand (context, id) {
        axios
            .delete(`/brands/${id}`)
            .then(response => {
                context.commit('REMOVE_BRAND', response.data.id)
            });
    }
}


export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
