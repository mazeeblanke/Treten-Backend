import { Form, Input, Button } from 'antd';
import React from 'react';
import queryString from 'query-string';
import notifier from 'simple-react-notifier';

class LoginForm extends React.Component {
  constructor (props) {
    super(props);
    this.handleSubmit = this.handleSubmit.bind(this)
    this.state = {
        isLoading: false
    }
  }

  handleSubmit(e) {
    const parsed = queryString.parse(location.search)
    const redirect = (parsed || {}).redirect
    e.preventDefault();
    this.setState({
        isLoading: true
    })
    this.props.form.validateFields((err, form) => {
      if (!err) {
        // console.log('Received values of form: ', form);
        axios.post('api/login', form).then((res) => {
            this.setState({
                isLoading: false
            });
            notifier.success('Successfully logged you in !')
            setTimeout(() => {
                window.location = redirect || '/';
            }, 2000)
        }).catch((err) => {
            // console.log(err.response.data);
            notifier.error('ERROR! '+err.response.data.message || 'Your credentials are incorrect !')
            let errors = err.response.data.errors || {};

            this.setState({
                isLoading: false
            })

            this.props.form.setFields({
              email: {
                errors: errors.email ? [new Error(errors.email[0])] : [],
              },
              password: {
                errors: errors.password ? [new Error(errors.password[0])] : [],
              },
            });
        })
      } else {
        this.setState({
          isLoading: false
        })
      }

    });

  }

  render() {
    const { getFieldDecorator } = this.props.form;
    return (
      <Form onSubmit={this.handleSubmit} className="login-form">

        <Form.Item>
          <label htmlFor="email">Email address</label>
          {getFieldDecorator('email', {
            rules: [{ required: true, message: 'Please input your email!' }],
          })(
            <Input
              size="large"
              type="email"
              placeholder="jonathanadamu@mymail.com"
            />,
          )}
        </Form.Item>

        <Form.Item>
          <label htmlFor="password">
            Password
            <span onClick={() => window.location = '/t/password/reset'}>Forgot password?</span>
          </label>
          {getFieldDecorator('password', {
            rules: [{ required: true, message: 'Please input your Password!' }],
          })(
            <Input.Password
              type="password"
              placeholder="Enter your password"
            />,
          )}
        </Form.Item>

        <Form.Item className="is-full-width">
          <Button
            disabled={this.state.isLoading}
            loading={this.state.isLoading}
            type="primary"
            htmlType="submit"
            className="auth__btn">
            Log in
          </Button>
        </Form.Item>

      </Form>
    );
  }
}

const WrappedLoginForm = Form.create({ name: 'login' })(LoginForm);

export default WrappedLoginForm;
