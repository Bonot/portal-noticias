<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Nova Notícia</h4>
            </div>
            <div class="card-body">
                <ul class="alert alert-warning" v-if="Object.keys(this.errorList).length > 0">
                    <li class="mb-0 ms-3" v-for="(error, index) in this.errorList" :key="index">
                        {{ error[0] }}
                    </li>
                </ul>
                <div class="mb-3">
                    <label>Título</label>
                    <input type="text" v-model="model.article.title" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Conteúdo</label>
                    <textarea v-model="model.article.content" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label>Autor</label>
                    <select v-model="model.article.author_id" class="form-select">
                        <option value="0" selected>Selecione um autor</option>
                        <option v-for="(author, index) in this.authors" :key="index"
                            :value="author.id">{{author.name}}</option>
                    </select>
                </div>
            <div class="mb-3">
                <button type="button" @click="updateArticle" class="btn btn-primary">Salvar</button>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'articleCreate',
    data(){
        return {
            errorList: '',
            model: {
                article: {
                    title: '',
                    content: '',
                    author_id: 0,
                }
            },
            authors: [],
        }
    },
    mounted(){
        this.articleId = this.$route.params.id;
        this.getArticlesData(this.articleId);
        this.getAuthors();
    },
    methods: {
        getAuthors() {
            axios.get(`http://localhost:90/api/authors`)
                .then(response => {
                    this.authors = response.data;
                });
        },
        getArticlesData(articleId) {
            axios.get(`http://localhost:90/api/articles/${articleId}`)
            .then(response => {
                this.model.article = response.data;
            })
        },
        updateArticle() {
            var myThis = this;
            axios.put(`http://localhost:90/api/articles/${this.articleId}`, this.model.article)
                .then(response => {
                    console.log(response);
                    if (response.data.success === false) {
                        myThis.errorList = response.data.errors;
                    }

                    if (response.status === 201) {
                        alert('Notícia editada com sucesso');
                        window.location.href = '/noticias';
                    }
                })
        }
    }
}
</script>
