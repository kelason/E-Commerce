<template>
    <v-app id="inspire">
        <v-row
            no-gutters
        >
            <v-col
                cols="12"
                sm="12"
                md="6"
                lg="7"
                class="hidden-xs-only"
            >
                <v-container
                    fill-height
                    fluid
                    class="svg__bg"
                >
                    <v-row 
                        class="text-sm-center" 
                        align="center" 
                        justify="center"
                        no-gutters
                    >
                        <v-col>
                            <h1 class="white--text display-3 font-weight-black">Welcome to E-Shop</h1>
                            <p class="grey--text text--lighten-4 headline mt-3 font-weight-black">Sign in to continue access.</p>
                            <div class="login__bg"></div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>
            <v-col
                cols="12"
                xs="12"
                sm="12"
                md="6"
                lg="5"
            >
                <v-container
                    fill-height
                    fluid
                >
                    <v-row 
                        class="px-16" 
                        align="center" 
                        justify="center"
                        no-gutters
                    >
                        <v-col>
                            <p class="baloo text-h4 mb-5">Sign In</p>
                            <v-form
                                ref="form"
                                v-model="valid"
                                @submit.prevent="submit"
                            >
                                <v-text-field
                                    v-model="login_details.email"
                                    :rules="emailRules"
                                    label="E-mail"
                                    required
                                    outlined
                                ></v-text-field>
                                <v-text-field
                                    v-model="login_details.password"
                                    :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'"
                                    :rules="[passwordRules.required, passwordRules.min]"
                                    :type="showPass ? 'text' : 'password'"
                                    label="Password"
                                    hint="At least 8 characters"
                                    class="input-group--focused"
                                    @click:append="showPass = !showPass"
                                    outlined
                                    required
                                ></v-text-field>
                                <v-btn
                                    block
                                    class="purple white--text"
                                    x-large
                                    :loading="loading"
                                    :disabled="!valid"
                                    type="submit"
                                >
                                    Sign In
                                </v-btn>
                                <p class="text-subtitle-1 mt-3"><v-icon>mdi-keyboard-backspace</v-icon> <router-link to="/" class="text-decoration-none">Go Back</router-link> </p>
                            </v-form>
                            <v-alert
                                color="red"
                                type="success"
                                v-if="message"
                            >
                                {{message}} 
                            </v-alert>
                        </v-col>
                    </v-row>
                </v-container>
            </v-col>
        </v-row>
    </v-app>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
    name: 'SignIn',
    data() {
        return {
            valid: true,
            showPass: false,
            login_details: {
                email: '',
                password: '',
            },
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            passwordRules: {
                required: value => !!value || 'Password Required.',
                min: value => value.length >= 8 || 'Min 8 characters',
                emailMatch: () => (`The email and password you entered don't match`),
            },
            loader: null,
            loading: false,
        }
    },
    computed: {
        ...mapGetters({
            message: 'auth/msg',
        }),
    },
    methods: {
        ...mapActions({signIn: 'auth/signIn'}),
        async submit() {
            var app = this;
            await app.signIn(app.login_details);
        },
    },
    created() {
        this.$store.commit('SET_LAYOUT', 'no-layout');
    },
}
</script>