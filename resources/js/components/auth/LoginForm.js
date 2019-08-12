import { Form, Icon, Input, Button, Checkbox, message } from 'antd';
import React from 'react';

class LoginForm extends React.Component {
  constructor (props) {
    super(props);
    this.handleSubmit = this.handleSubmit.bind(this)
    this.state = {
        isLoading: false
    }
  }

  handleSubmit(e) {
    e.preventDefault();
    this.setState({
        isLoading: true
    })
    this.props.form.validateFields((err, form) => {
      if (!err) {
        console.log('Received values of form: ', form);
        axios.post('login', form).then((res) => {
            console.log(res.data)
            this.setState({
                isLoading: false
            });
            message.success('Successfully logged you in !', 21);
            setTimeout(() => {
                window.location = '/';
            }, 2000)
        }).catch((err) => {
            console.log(err.response.data);
            message.error('Your credentials are incorrect !', 21);
            this.setState({
                isLoading: false
            })
        })
      }
    });

    // console.log(this.props.form.submit());
  };

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
            <span>Forgot password?</span>
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
          {/* {getFieldDecorator('remember', {
            valuePropName: 'checked',
            initialValue: true,
          })(<Checkbox>Remember me</Checkbox>)}
          <a className="login-form-forgot" href="">
            Forgot password
          </a> */}
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
