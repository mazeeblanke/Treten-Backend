import React from 'react';
import { Form, Icon, Input, Button, Checkbox } from 'antd';
import ReactPhoneInput from 'react-phone-input-2'
import 'react-phone-input-2/dist/style.css'

// const PhoneInput = dynamic(() => import('react-phone-input-2'), { ssr: false });

class RegisterForm extends React.Component {

//   state = {
//     phone: ''
//   }

  constructor (props) {
      super(props);
      this.state = {
          phone: ''
      }
  }

  handleSubmit(e) {
    e.preventDefault();
    this.props.form.validateFields((err, values) => {
      if (!err) {
        console.log('Received values of form: ', values);
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
              type="email"
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
              type="email"
              placeholder="Doe"
            />
          )}
        </Form.Item>

        <Form.Item>
          <label htmlFor="email">Other name (optional)</label>
            <Input
              size="large"
              type="email"
              placeholder="Adamu"
            />
        </Form.Item>

        <Form.Item>
          <label htmlFor="Phone number">Phone number</label>
          <ReactPhoneInput
            className="has-full-width"
            defaultCountry="ng"
            value={this.state.phone}
          />
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

const WrappedRegisterForm = Form.create({ name: 'register_form' })(RegisterForm);

export default WrappedRegisterForm;
