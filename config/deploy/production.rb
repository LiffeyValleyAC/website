role :web, %w{mike@new.liffeyvalleyac.com}

set :ssh_options, {
  forward_agent: false,
}
