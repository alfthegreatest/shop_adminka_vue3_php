import axios from 'axios';

// Общие данные
const state = {
    all: []
};

// Геттеры
const getters = {
    getCategory: state => id => {
        return _.find(state.all, { id: id });
    }
};

// Мутации для изменения данных
const mutations = {
    SET_CATEGORIES (state, categories) {
        state.all = categories
    }
};

// Действия: ходим на сервер и вызываем мутации
const actions = {
    getCategories (context) {
        axios
            .get('/categories')
            .then(response => {
                context.commit('SET_CATEGORIES', response.data.records)
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
