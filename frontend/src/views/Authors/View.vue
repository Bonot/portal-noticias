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

export default {
    name: 'authors',
    components: {
        Pagination,
    },
    data() {
        return {
            authors: [],
            offset: 0,
            limit: 10,
            total: 0,
        }
    },
    mounted(){
        this.getAuthors();
    },
    methods: {
        getAuthors() {
            axios.get(`http://localhost:90/api/authors?page=${this.offset}&size=${this.limit}&paginate=true`)
                .then(response => {
                    this.authors = response.data.data;
                    this.total = response.data.total;
                });
        },
        deleteAuthor(authorId) {
            if (confirm('Você tem certeza que gostaria de excluir esse registro?')) {
                axios.delete(`http://localhost:90/api/authors/${authorId}`)
                    .then(response => {
                        alert('Registro removido com sucesso');
                        location.reload();
                    });
            }
        },
        changePage(value) {
            this.offset = value;
            this.getAuthors();
        },
    }
}
</script>
