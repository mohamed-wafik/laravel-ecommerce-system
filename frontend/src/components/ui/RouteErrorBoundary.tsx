import { Component, type ReactNode } from "react";
import ErrorPage from "./ErrorHandler";

interface Props {
  children: ReactNode;
}

interface State {
  hasError: boolean;
  error: Error | null;
}

class RouteErrorBoundary extends Component<Props, State> {
  constructor(props: Props) {
    super(props);
    this.state = { hasError: false, error: null };
  }

  static getDerivedStateFromError(error: Error): State {
    return { hasError: true, error };
  }

  componentDidCatch(error: Error, info: React.ErrorInfo) {
    console.error("Route error caught:", error, info);
  }

  render() {
    if (this.state.hasError) {
      return <ErrorPage message={this.state.error?.message} />;
    }
    return this.props.children;
  }
}

export default RouteErrorBoundary;
