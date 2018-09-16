<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Event Log</div>

                    <div class="card-body">
                        <div v-if="events.length">
                                <div v-for="event in events" class="log-event">
                                    <div class="event-container">{{event.name}} : <span class="time-stamp">{{event.created_at}}</span></div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .card-header {
        font-size: 1.5em;
        font-weight: 600;
        margin-bottom: 25px;
        margin-top: 20px;
    }
    .log-event {
        background-color: #d0211c;
        margin-top: 9px;
        text-transform: uppercase;
    }
    .event-container {
        background-color: #fafbdc;
        padding: 20px 5px;
        margin: 2px;
    }
    .time-stamp {
        font-size: .85em;
        font-weight: 200;
    }
</style>
<script>
    export default {
        props: ['deployment'],
        data() {
            return {
                deployment_id: '',
                complete: false,
                events: []
            }
        },
        mounted() {
            setInterval(() => {
                axios.get('/deployments/latest')
                    .then(response => {
                        this.deployment_id = response.data.id;
                        if (this.deployment_id === this.deployment.id) {
                            if (this.complete === false) {
                                axios.get('/deployments/' + this.deployment.id)
                                    .then(response => {
                                        this.events = response.data.events;
                                        if (this.events.find(function(event) {
                                            return event.name === 'deployed';
                                        })) {
                                            this.complete = true;
                                        }
                                    });
                            }
                        } else {
                            window.location.reload();
                        }
                    });
            }, 1000);
        }
    }
</script>
