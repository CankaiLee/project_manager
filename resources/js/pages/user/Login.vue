<template>
    <div class="main">
        <a-form-model
            ref="ruleForm"
            :model="form"
            :rules="rules"
            :label-col="labelCol"
            :wrapper-col="wrapperCol"
        >
            <a-form-model-item ref="email" label="Email" prop="email">
                <a-input
                    v-model="form.email"
                    @blur="() => {$refs.email.onFieldBlur();}"
                />
            </a-form-model-item>

            <a-form-model-item ref="password" label="Password" prop="password">
                <a-input
                    type="password"
                    v-model="form.password"
                    @blur="() => {$refs.password.onFieldBlur();}"
                />
            </a-form-model-item>

            <a-form-model-item :wrapper-col="{ span: 14, offset: 4 }">
                <a-button type="primary" @click="onSubmit">
                    Create
                </a-button>
                <a-button style="margin-left: 10px;" @click="resetForm">
                    Reset
                </a-button>
            </a-form-model-item>
        </a-form-model>
    </div>
</template>

<script>
    import {mapActions, mapMutations} from "vuex";

    export default {
        name: "Login",
        data () {
            return {
                labelCol: { span: 4 },
                wrapperCol: { span: 14 },
                form: {
                    email: '',
                    password: ''
                },
                rules: {
                    email: [
                        { required: true, message: '邮箱不能为空', trigger: 'blur' }
                    ],
                    password: [
                        { required: true, message: '密码不能为空', trigger: 'blur' },
                        { min: 6,  message: '密码长度不能小于6位', trigger: 'blur' },
                    ]
                }
            }
        },
        methods: {
            ...mapMutations(['changeLogin']),
            onSubmit() {
                let that = this;
                this.$refs.ruleForm.validate(valid => {
                    if (valid) {
                        let uri = '/api/user/login';
                        console.log(that.form.email);
                        that.$http.post(uri,  {
                            email: that.form.email,
                            password: that.form.password
                        }, {
                            timeout: 3000
                        }).then((result) => {
                            let data = result.data.data;
                            let token = data.token;
                            that.changeLogin({ token: token });
                            that.$router.push('/dashboard');
                        });
                    }
                });
            },
            resetForm() {
                this.$refs.ruleForm.resetFields();
            },
        }
    }
</script>

<style scoped>

</style>
