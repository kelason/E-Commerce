<template>
    <v-app id="inspire"
    >
        <v-container
            fill-height 
            fluid
        >
            <v-row 
                align="center"
                justify="center"
            >
                <v-col
                    class="mx-auto"
                    cols="6"
                    lg="6"
                    md="6"
                    sm="6"
                >
                    <div class="font-weight-black text-h3 text-center green--text">
                        Payment Successful <v-icon class="green--text" x-large>mdi-check-bold</v-icon>
                    </div>
                </v-col>
            </v-row>

            <v-row 
                align="center"
                justify="center"
            >
                <v-col
                    class="mx-auto"
                    cols="3"
                    lg="3"
                    md="3"
                    sm="3"
                >
                    <router-link
                        class="text-decoration-none"
                        :to="`/`"
                    >
                        <v-btn
                            color="orange"
                            dark
                            block
                        >
                            Shop For More
                        </v-btn>
                    </router-link>
                </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<script>

</script>