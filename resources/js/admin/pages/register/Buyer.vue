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
                            <p class="grey--text text--lighten-4 headline mt-3 font-weight-black">Shop now we deliver.</p>
                            <div class="cart__bg"></div>
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
                        class="px-16 mt-5" 
                        align="center" 
                        justify="center"
                        no-gutters
                    >
                        <v-col>
                            <p class="baloo text-h4 mb-5">Sign Up as Buyer</p>
                            <!-- <v-btn
                                block
                                outlined
                                x-large
                                class="mb-5 d-flex justify-space-between"
                            >
                                <v-icon
                                    x-large
                                >
                                    mdi-google
                                </v-icon>
                                <div>
                                    Sign Up with Google
                                </div>
                                <div></div>
                            </v-btn>
                            <v-btn
                                block
                                outlined
                                x-large
                                class="mb-5  d-flex justify-space-between"
                            >
                                <v-icon
                                    x-large
                                >
                                    mdi-facebook
                                </v-icon>
                                <div>
                                    Sign Up with Facebook
                                </div>
                                <div></div>
                            </v-btn>
                            <h3 class="line-between mb-4">OR</h3> -->
                            <v-form
                                ref="form"
                                v-model="valid"
                                @submit.prevent="validate"
                            >
                                <v-text-field
                                    v-model="register.email"
                                    :rules="emailRules"
                                    label="E-mail"
                                    required
                                    outlined
                                ></v-text-field>
                                <v-text-field
                                    v-model="register.name"
                                    type="text"
                                    :rules="nameRules"
                                    label="Name"
                                    outlined
                                    required
                                ></v-text-field>
                                <v-text-field
                                    v-model="register.phone_number"
                                    :rules="phoneRules"
                                    label="Phone Number"
                                    prefix="+ 63"
                                    required
                                    outlined
                                    type="number"
                                ></v-text-field>
                                <v-text-field
                                    v-model="register.password"
                                    :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'"
                                    :type="showPass ? 'text' : 'password'"
                                    label="Password"
                                    class="input-group--focused"
                                    @click:append="showPass = !showPass"
                                    outlined
                                    required
                                ></v-text-field>
                                <v-text-field
                                    v-model="register.confirm_password"
                                    :append-icon="showPass ? 'mdi-eye' : 'mdi-eye-off'"
                                    :rules="[passwordRules.required, passwordRules.min]"
                                    :type="showPass ? 'text' : 'password'"
                                    label="Confirm Password"
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
                                    Sign Up
                                </v-btn>
                                <p class="text-subtitle-1 mt-3"><v-icon>mdi-keyboard-backspace</v-icon> <router-link to="/" class="text-decoration-none">Go Back</router-link> </p>
                            </v-form>
                            <v-alert
                                type="success"
                                v-if="message"
                            >
                                {{message}} click <router-link to="/signIn">here</router-link> to login
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
    data() {
        return {
            valid: true,
            showPass: false,
            showPassConfirm: false,
            register: {
                name: '',
                email: '',
                password: '',
                phone_number: '',
                user_type: 'Buyer',
                confirm_password: '',
            },
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
            ],
            nameRules: [
                v => !!v || 'Name is required',
                v => v.length >= 5 || 'Name must greater than 5 characters',
            ],
            phoneRules: [
                v => !!v || 'Phone is required',
                v => v.length == 10 || 'Phone number must be valid',
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
    watch: {
    },
    computed: {
        ...mapGetters({
            message: 'auth/msg'
        }),
    },
    created() {
        this.$store.commit('SET_LAYOUT', 'no-layout')
    },
    methods: {
        ...mapActions({signUp: 'auth/signUp'}),
        async validate () {
            var app = this;
            await app.signUp(app.register);
            app.register = []
        },
    }
}
</script>