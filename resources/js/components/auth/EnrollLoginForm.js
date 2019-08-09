import React from 'react';
import { Form, Icon, Input, Button, Checkbox } from 'antd';
import ReactPhoneInput from 'react-phone-input-2'
import dynamic from 'next/dynamic';
import 'react-phone-input-2/dist/style.css'

class EnrollLoginForm extends React.Component {

  state = {
    phone: ''
  }

  handleSubmit = e => {
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
      <Form onSubmit={this.handleSubmit} className="enroll-register-form">

        <div className="row">
          <div className="col-md-6">
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
          </div>
          <div className="col-md-6">
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
          </div>
        </div>

        <Form.Item className="is-full-width">
          <Button size="large" type="danger">Proceed</Button>
        </Form.Item>

      </Form>
    );
  }
}

const WrappedEnrollLoginForm = Form.create({ name: 'enroll_register_form' })(EnrollLoginForm);

export default WrappedEnrollLoginForm;