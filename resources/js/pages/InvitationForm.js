import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Tabs, Button, Input, Form } from 'antd';
import notifier from 'simple-react-notifier';
import withAuthLayout from '../layouts/withAuthLayout';
import ReactDOM from 'react-dom';

const { TabPane } = Tabs;

class InvitationForm extends Component {
  constructor(props) {
    super(props);
    this.handleSubmit = this.handleSubmit.bind(this)
    this.state = {
      isAcceptingInvitation: false
    }
  }

  handleSubmit (e) {
    e.preventDefault();
    this.setState({
      isAcceptingInvitation: true
    })
    this.props.form.validateFieldsAndScroll((err, form) => {
      if (!err) {
        axios.post('/t/api/invitations/complete', {
          ...form,
          token: location.href.substring(location.href.lastIndexOf('/') + 1)
        }).then((res) => {
          this.setState({
            isAcceptingInvitation: false
          });
          notifier.success(res.data.message)
          window.location = '/d/profile/details';
        }).catch((err) => {
          notifier.error('ERROR! '+err.response.data.message)
          this.setState({
            isAcceptingInvitation: false
          })
        })
      } else {
        this.setState({
          isAcceptingInvitation: false
        })
      }
    })
  }

  render() {
    const { getFieldDecorator } = this.props.form;
    return (
      <section className="auth has-grey-bg has-min-full-vh pb-5">
        <div className="container pt-5 has-full-height">
          <div className="row justify-content-center has-full-height">
            <div className="col-md-5 auth__wrapper">
              <div className="auth__container">
                <Tabs className="has-full-height resetpassword " type="card">
                  <TabPane className="has-full-height pr-8 pl-8" tab="Accept Invitation" key="accept-invitation">
                    <p className="register__sub-text text-center">
                      Fill the form to create your account
                    </p>
                    <Form className="login-form">
                      <Form.Item>
                        <label htmlFor="first_name">First Name</label>
                        {getFieldDecorator('firstName', {
                          rules: [{ required: true, message: 'Please input your first name!' }],
                        })(
                          <Input
                            size="large"
                            type="text"
                            placeholder="John"
                          />,
                        )}
                      </Form.Item>
                      <Form.Item>
                        <label htmlFor="last_name">Last Name</label>
                        {getFieldDecorator('lastName', {
                          rules: [{ required: true, message: 'Please input your last name!' }],
                        })(
                          <Input
                            size="large"
                            type="text"
                            placeholder="Doe"
                          />,
                        )}
                      </Form.Item>
                      <Form.Item>
                        <label htmlFor="password">Password</label>
                        {getFieldDecorator('password', {
                          rules: [{ required: true, message: 'Please input your password!' }],
                        })(
                          <Input
                            size="large"
                            type="password"
                            placeholder="your password"
                          />,
                        )}
                      </Form.Item>
                      <Form.Item>
                        <label htmlFor="password-confirmation">Password Confirmation</label>
                        {getFieldDecorator('passwordConfirmation', {
                          rules: [{
                            required: true,
                            message: 'Please enter your new password confirmation! Passwords must be same',
                            min: 8,
                            validator: (rule, value, cb) =>
                              value === this.props.form.getFieldValue('password')
                                ? cb()
                                : cb(value) }],
                        })(
                          <Input
                            size="large"
                            type="password"
                            placeholder="Confirm password"
                          />,
                        )}
                      </Form.Item>

                      <Form.Item className="is-full-width mt-5 mb-3">
                        <Button
                          disabled={this.state.isAcceptingInvitation}
                          loading={this.state.isAcceptingInvitation}
                          type="primary"
                          onClick={this.handleSubmit}
                          className="auth__btn">
                          Accept Invitation
                        </Button>
                      </Form.Item>
                    </Form>
                  </TabPane>
                </Tabs>
              </div>
            </div>
          </div>
        </div>
      </section>
    );
  }
}

InvitationForm.propTypes = {

};

const WrappedInvitationFormForm = Form.create({ name: 'login' })(
	InvitationForm
);

InvitationForm = withAuthLayout(WrappedInvitationFormForm);

if (document.getElementById('invitation')) {
	ReactDOM.render(
		<InvitationForm />,
		document.getElementById('invitation')
	);
}
