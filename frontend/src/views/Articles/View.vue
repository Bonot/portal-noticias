<template>
  <div class="container">
    <div class="card">
        <div class="card-header">
            <h4>
                Notícias
                <RouterLink to="/noticias/novo" class="btn btn-primary float-end">Adicionar notícia</RouterLink>
            </h4>
        </div>
        <div class="card-body">
            <nav class="navbar bg-body-tertiary">
                <form class="d-flex" role="filter">
                <input class="form-control me-2" v-model="searchInput.query" type="search" placeholder="Buscar notícia" aria-label="Search">
                <author-select v-model="searchInput.author_id" />
                <button type="button" class="btn btn-outline-success" @click="filterArticles()">Filtrar</button>
                </form>
            </nav>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Título</th>
                        <th>Data de criação</th>
                        <th>Última atualização</th>
                        <th>Autor</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody v-if="this.articles.length > 0">
                    <tr v-for="(article, index) in this.articles" :key="index">
                        <td>{{ article.id }}</td>
                        <td>{{ article.title }}</td>
                        <td>{{ formatDate(article.created_at) }}</td>
                        <td>{{ formatDate(article.updated_at) }}</td>
                        <td>{{ article.author.name  }}</td>
                        <td class="w-25 text-end">
                            <RouterLink :to="{ path: '/noticias/' + article.id + '/editar' }" class="btn btn-success">Editar</RouterLink>
                            <button type="button" @click="deleteArticle(article.id)" class="btn btn-danger">Excluir</button>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="5">Nenhum dado encontrado</td>
                    </tr>
                </tbody>
            </table>
            <pagination
                v-if="this.articles.length > 0"
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
import AuthorSelect from '../../components/AuthorSelect.vue';

export default {
    name: 'articles',
    components: {
        Pagination,
        AuthorSelect,
    },
    data() {
        return {
            articles: [],
            searchInput: {
                query: '',
                author_id: 0,
            },
            offset: 0,
            limit: 10,
            total: 0,
        }
    },
    mounted(){
        this.getArticles();
    },
    methods: {
        getArticles() {
            axios.get(`http://localhost:90/api/articles?page=${this.offset}&size=${this.limit}&paginate=true`)
                .then(response => {
                    this.articles = response.data.data;
                    this.total = response.data.total;
                });
        },
        deleteArticle(articleId) {
            if (confirm('Você tem certeza que gostaria de excluir esse registro?')) {
                axios.delete(`http://localhost:90/api/articles/${articleId}`)
                    .then(response => {
                        alert('Registro removido com sucesso');
                        location.reload();
                    });
            }
        },
        changePage(value) {
            this.offset = value;
            this.getArticles();
        },
        formatDate(dateString) {
            if (dateString) {
                const date = new Date(dateString);
                return new Intl.DateTimeFormat('default', {dateStyle: 'long'}).format(date);
            }
        },
        filterArticles() {
            axios.get(`http://localhost:90/api/articles?page=${this.offset}&size=${this.limit}&paginate=true&query=${this.searchInput.query}&author_id=${this.searchInput.author_id}`)
                .then(response => {
                    this.articles = response.data.data;
                    this.total = response.data.total;
                });
        },
    }
}
</script>
