<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Event Log</div>

                    <div class="card-body">
                        <div v-if="events.length">
                            <ul>
                                <li v-for="event in events">
                                    {{event.name}} : <small>{{event.created_at}}</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['deployment'],
        data() {
            return {
                deployment_id: '',
                events: []
            }
        },
        mounted() {
            let that = this;
            setInterval( () => {
                axios.get('/deployments/latest')
                    .then(response => {
                        this.deployment_id = response.data.id;
                        if (this.deployment_id === this.deployment.id) {
                            console.log('same deployment');
                            console.log(this.deployment.id);
                            axios.get('/deployments/'+this.deployment.id)
                                .then(response => {
                                    this.events = response.data.events;
                                });
                        } else {
                            window.location = window.location;
                        }
                    });
            }, 250);
        }
    }
</script>
