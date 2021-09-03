<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Component com "Two Way Data Binding"</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="" class="form-label">Digite algo:</label>
                                <input type="text" v-model="texto" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">O que você digitar aparecerá aqui:</div>
                            <div class="col-12">
                                <blockquote class="blockquote">
                                    {{texto}}
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    blockquote{
        background: rgba(204, 204, 204, 0.226);
        font-style:italic;
        font-size: 1em;

    }
</style>
<script>
    export default {
        data(){
            return{
                texto:''
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
