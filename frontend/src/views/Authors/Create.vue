<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Novo autor</h4>
            </div>
            <div class="card-body">
                <ul class="alert alert-warning" v-if="Object.keys(this.errorList).length > 0">
                    <li class="mb-0 ms-3" v-for="(error, index) in this.errorList" :key="index">
                        {{ error[0] }}
                    </li>
                </ul>
                <div class="mb-3">
                    <label>Nome</label>
                    <input type="text" v-model="model.author.name" class="form-control">
                </div>
            <div class="mb-3">
                <button type="button" @click="saveAuthor" class="btn btn-primary">Salvar</button>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import Cookie from 'js-cookie'

export default {
    name: 'authorCreate',
    data(){
        return {
            errorList: '',
            model: {
                author: {
                    name: '',
                }
            },
            token: '',
            config: {}
        }
    },
    mounted() {
        this.token = Cookie.get('token')
        this.config = {
            headers: { 
                Authorization: `Bearer ${this.token}`
            }
        }
    },
    methods: {
        saveAuthor() {
            var myThis = this;
            axios.post('/api/authors', this.model.author, this.config)
                .then(response => {
                    if (response.data.success === false) {
                        myThis.errorList = response.data.errors;
                    }

                    if (response.status === 201) {
                        window.location.href = '/autores';
                    }
                })
        }
    }
}
</script>
