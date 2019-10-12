import React from 'react';
import { Form, Icon, Input, Button, Checkbox, message } from 'antd';
import ReactPhoneInput from 'react-phone-input-2'
import 'react-phone-input-2/dist/style.css'

class RegisterForm extends React.Component {

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
        axios.post('api/register', { as: 'student', ...form }).then((res) => {
            console.log(res.data)
            this.setState({
                isLoading: false
            });
            message.success('Registration Successful', 21);
            setTimeout(() => {
                window.location = '/';
            }, 2000)
        }).catch((err) => {
            message.error(err.response.data.message || 'The Form contains some errors', 21);
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
  };

  render() {
    const { getFieldDecorator } = this.props.form;
    return (
      <Form onSubmit={this.handleSubmit} className="login-form">
        <Form.Item>
          <label htmlFor="email">First name</label>
          {getFieldDecorator('first_name', {
            rules: [{ required: true, message: 'Please input your first name!' }],
          })(
            <Input
              size="large"
              type="text"
              placeholder="Jonathan"
            />,
          )}
        </Form.Item>

        <Form.Item>
          <label htmlFor="email">Last name</label>
          {getFieldDecorator('last_name', {
            rules: [{ required: true, message: 'Please input your last name!' }],
          })(
            <Input
              size="large"
              type="text"
              placeholder="Doe"
            />
          )}
        </Form.Item>

        <Form.Item>
          <label htmlFor="email">Other name (optional)</label>
            <Input
              size="large"
              type="text"
              placeholder="Adamu"
            />
        </Form.Item>

        <Form.Item>
          <label htmlFor="Phone number">Phone number</label>
          {
            getFieldDecorator('phone_number', {
              rules: [{ required: true, message: 'Please input your phone number' }]
            })(
              <ReactPhoneInput
                className="has-full-width"
                defaultCountry="ng"
                value={this.state.phone}
              />
            )
          }
        </Form.Item>

        <Form.Item className="mt-3">
          <label htmlFor="email">Email address</label>
          {getFieldDecorator('email', {
            rules: [{ required: true, message: 'Please input your email!' }],
          })(
            <Input
              size="large"
              type="email"
              placeholder="Email"
            />,
          )}
        </Form.Item>

        <Form.Item>
          <label htmlFor="password">
            Password
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
            Register
          </Button>
        </Form.Item>

      </Form>
    );
  }
}

const WrappedRegisterForm = Form.create({ name: 'register_form' })(RegisterForm);

export default WrappedRegisterForm;
