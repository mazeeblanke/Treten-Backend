import React from 'react';
import { Form, Input, Button } from 'antd';
import ReactPhoneInput from 'react-phone-input-2'
import 'react-phone-input-2/dist/style.css'

const { TextArea } = Input;

// const PhoneInput = dynamic(() => import('react-phone-input-2'), { ssr: false });

class InstructorForm extends React.Component {

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
      <Form onSubmit={this.handleSubmit} className="instructor-form pb-5">

        <Form.Item>
          <label className="fw-600" htmlFor="email">First name</label>
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
          <label className="fw-600" htmlFor="email">Last name</label>
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
          <label className="fw-600" htmlFor="email">Other name (optional)</label>
            <Input
              size="large"
              type="email"
              placeholder="Adamu"
            />
        </Form.Item>

        <Form.Item>
          <label className="fw-600" htmlFor="email">Qualifications</label>
            <Input
              size="large"
              type="text"
              placeholder="Enter the qualifications you possess"
            />
        </Form.Item>

        <Form.Item>
          <label className="fw-600" htmlFor="email">Why are you a good fit for Treten Academy?</label>
          <TextArea rows={4} placeholder="Go into details about why you will like to be an instructor" />
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
          <label htmlFor="Phone number">Phone number</label>
          <ReactPhoneInput
            className="has-full-width"
            style={{width: '100%'}}
            defaultCountry="ng"
            value={this.state.phone}
          />
        </Form.Item>

        <Form.Item className="is-full-width mt-23">
          <Button
            type="primary"
            htmlType="submit"
            className="auth__btn">
            Submit
          </Button>
        </Form.Item>

      </Form>
    );
  }
}

const WrappedInstructorForm = Form.create({ name: 'register_form' })(InstructorForm);

export default WrappedInstructorForm;
