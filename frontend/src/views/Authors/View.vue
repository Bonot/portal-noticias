<template>
  <div class="container">
    <div class="card">
        <div class="card-header">
            <h4>
                Autores
                <RouterLink to="/autores/novo" class="btn btn-primary float-end">Adicionar autor</RouterLink>
            </h4>
        </div>
        <div class="card-body">
            <nav class="navbar bg-body-tertiary">
                <form class="d-flex" role="filter">
                <input class="form-control me-2" v-model="searchInput.name" type="search" placeholder="Buscar autor" aria-label="Search">
                <button type="button" class="btn btn-outline-success" @click="filterAuthors()">Filtrar</button>
                </form>
            </nav>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody v-if="this.authors.length > 0">
                    <tr v-for="(author, index) in this.authors" :key="index">
                        <td>{{ author.id }}</td>
                        <td>{{ author.name }}</td>
                        <td class="w-25 text-end">
                            <RouterLink :to="{ path: '/autores/' + author.id + '/editar' }" class="btn btn-success">Editar</RouterLink>
                            <button type="button" @click="deleteAuthor(author.id)" class="btn btn-danger">Excluir</button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="3">Nenhum dado encontrado</td>
                    </tr>
                </tbody>
            </table>
            <pagination
                v-if="this.authors.length > 0"
                :offset="this.offset"
                :total="this.total"
                :limit="this.limit"
                @change-page="changePage"
            />
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Pagination from '../../components/Pagination.vue';
import Cookie from 'js-cookie'

export default {
    name: 'authors',
    components: {
        Pagination,
    },
    data() {
        return {
            authors: [],
            searchInput: {
                name: '',
            },
            offset: 0,
            limit: 10,
            total: 0,
            token : '',
            config : {}
        }
    },
    mounted(){
        this.token = Cookie.get('token')
        this.config = {
            headers: { 
                Authorization: `Bearer ${this.token}`
            }
        }
        this.getAuthors();
    },
    methods: {
        getAuthors() {
            axios.get(`/api/authors?page=${this.offset}&size=${this.limit}`, this.config)
                .then(response => {
                    this.authors = response.data.data;
                    this.total = response.data.total;
                });
        },
        deleteAuthor(authorId) {
            if (confirm('Você tem certeza que gostaria de excluir esse registro?')) {
                axios.delete(`/api/authors/${authorId}`, this.config)
                    .then(response => {
                        if (response.data.success === false)
                        {
                            alert(response.data.message);
                        } else {
                            alert('Registro removido com sucesso');
                            location.reload();
                        }
                    });
            }
        },
        changePage(value) {
            this.offset = value;
            this.getAuthors();
        },
        filterAuthors() {
            axios.get(`/api/authors?page=${this.offset}&size=${this.limit}&name=${this.searchInput.name}`, this.config)
                .then(response => {
                    this.authors = response.data.data;
                    this.total = response.data.total;
                });
        },
    }
}
</script>
